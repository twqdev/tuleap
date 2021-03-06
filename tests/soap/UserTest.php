<?php
/**
 * Copyright (c) Enalean, 2015. All rights reserved
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/
 */

require_once dirname(__FILE__).'/../lib/autoload.php';

/**
 * @group UserTest
 */
class UserTest extends SOAPBase {

    public function setUp() {
        parent::setUp();

        $_SERVER['SERVER_NAME'] = $this->server_name;
        $_SERVER['SERVER_PORT'] = $this->server_port;
        $_SERVER['SCRIPT_NAME'] = $this->base_wsdl;
    }

    public function tearDown() {
        unset($_SERVER['SERVER_NAME']);
        unset($_SERVER['SERVER_PORT']);
        unset($_SERVER['SCRIPT_NAME']);

        parent::tearDown();
    }

    public function testGetUserInfo() {
        $session_hash = $this->getSessionHash();

        $response = $this->soap_base->getUserInfo(
            $session_hash,
            SOAP_TestDataBuilder::TEST_USER_1_ID
        );

        $this->assertEquals($response->identifier, SOAP_TestDataBuilder::TEST_USER_1_ID);
        $this->assertEquals($response->id, SOAP_TestDataBuilder::TEST_USER_1_ID);
        $this->assertEquals($response->username, SOAP_TestDataBuilder::TEST_USER_1_NAME);
        $this->assertEquals($response->real_name, SOAP_TestDataBuilder::TEST_USER_1_REALNAME);
        $this->assertEquals($response->email, SOAP_TestDataBuilder::TEST_USER_1_EMAIL);
        $this->assertEquals($response->ldap_id, SOAP_TestDataBuilder::TEST_USER_1_LDAPID);
    }
}