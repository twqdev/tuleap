<?php
/**
 * Copyright (c) Enalean, 2014. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */


class LDAP_SearchPeople extends Search_SearchPeople {
    /** @var UserManager */
    private $manager;

    /** @var LDAP */
    private $ldap;

    public function __construct(UserManager $manager, LDAP $ldap) {
        $this->manager = $manager;
        $this->ldap    = $ldap;
    }

    public function search(Search_SearchQuery $query, $limit) {
        if (! $this->ldap->connect()) {
            $users = array();
        } else {
            $users = $this->getMatchingUsers($query, $limit);
        }

        return new Search_SearchResultsPresenter(
            new Search_SearchResultsIntroPresenter($users, $query->getWords()),
            $users,
            self::NAME
        );
    }

    private function getMatchingUsers(Search_SearchQuery $query, $limit) {
        $users = array();
        $ldap_result_iterator  = $this->ldap->searchUser($query->getWords());
        if ($ldap_result_iterator->count() > 0) {
            $ldap_result_iterator->count();

            $ldap_result_iterator->seek($query->getOffset());
            while ($ldap_result_iterator->valid() && $limit > 0) {
                $ldap_result = $ldap_result_iterator->current();
                $users[] = $this->getUserPresenter($ldap_result);
                $ldap_result_iterator->next();
                $limit--;
            }
        }
        return $users;
    }

    private function getUserPresenter(LDAPResult $ldap_result) {
        $directory_uri = $this->buildLinkToDirectory($ldap_result, $ldap_result->getCommonName());
        $user          = $this->manager->getUserByLdapId($ldap_result->getEdUid());
        if ($user) {
            return new LDAP_SearchPeopleResultPresenter($user->getRealName(), $user->getAvatarUrl(), $directory_uri, $user->getUnixName());
        }
        return new LDAP_SearchPeopleResultPresenter($ldap_result->getCommonName(), PFUser::DEFAULT_AVATAR_URL, $directory_uri);
    }

    private function buildLinkToDirectory(LDAPResult $lr, $value) {
        include_once($GLOBALS['Language']->getContent('directory_redirect', 'en_US', 'ldap'));
        if(function_exists('custom_build_link_to_directory')) {
            return custom_build_link_to_directory($lr, $value);
        }
        return '';
    }
}
