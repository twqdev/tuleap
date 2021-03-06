<?php
/**
 * Copyright (c) Enalean, 2015. All Rights Reserved.
 * Copyright (c) Xerox Corporation, Codendi Team, 2001-2009. All rights reserved
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
 * along with Tuleap; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


class Tracker_SemanticFactory {

    /**
     * Hold an instance of the class
     */
    protected static $instance;

    /**
     * The singleton method
     *
     * @return Tracker_SemanticFactory an instance of the factory
     */
    public static function instance() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * Creates a Tracker_Semantic Object
     *
     * @param SimpleXMLElement $xml         containing the structure of the imported semantic
     * @param array            &$xmlMapping containig the newly created formElements idexed by their XML IDs
     * @param Tracker          $tracker     to which the semantic is attached
     *
     * @return Tracker_Semantic The semantic object, or null if error
     */
    public function getInstanceFromXML($xml, &$xmlMapping, $tracker) {
        $semantic = null;
        $attributes = $xml->attributes();
        $type = $attributes['type'];
        switch($type) {
            case 'title':
                $semantic = $this->getSemanticTitleFactory()->getInstanceFromXML($xml, $xmlMapping, $tracker);
                break;
            case 'description':
                $semantic = $this->getSemanticDescriptionFactory()->getInstanceFromXML($xml, $xmlMapping, $tracker);
                break;
            case 'status';
                $semantic = $this->getSemanticStatusFactory()->getInstanceFromXML($xml, $xmlMapping, $tracker);
                break;
            case 'contributor';
                $semantic = $this->getSemanticContributorFactory()->getInstanceFromXML($xml, $xmlMapping, $tracker);
                break;
            case 'tooltip';
                $semantic = $this->getSemanticTooltipFactory()->getInstanceFromXML($xml, $xmlMapping, $tracker);
                break;
            default:
                $semantic = $this->getSemanticFromAnotherPlugin($xml, $xmlMapping, $tracker, $type);
                break;
        }

        return $semantic;
    }

    private function getSemanticFromAnotherPlugin(SimpleXMLElement $xml, array $xml_mapping, Tracker $tracker, $type) {
        $semantic = null;

        EventManager::instance()->processEvent(
            TRACKER_EVENT_SEMANTIC_FROM_XML,
            $m = array(
                'xml'           => $xml,
                'xml_mapping'   => $xml_mapping,
                'tracker'       => $tracker,
                'semantic'      => &$semantic,
                'type'          => $type,
            )
        );

        return $semantic;
    }


    /**
     * Returns an instance of Tracker_Semantic_TitleFactory
     *
     * @return Tracker_Semantic_TitleFactory an instance of the factory
     */
    function getSemanticTitleFactory() {
        return Tracker_Semantic_TitleFactory::instance();
    }

    /**
     * Returns an instance of Tracker_Semantic_TitleFactory
     *
     * @return Tracker_Semantic_TitleFactory an instance of the factory
     */
    function getSemanticDescriptionFactory() {
        return Tracker_Semantic_DescriptionFactory::instance();
    }

    /**
     * Returns an instance of Tracker_Semantic_StatusFactory
     *
     * @return Tracker_Semantic_StatusFactory an instance of the factory
     */
    function getSemanticStatusFactory() {
        return Tracker_Semantic_StatusFactory::instance();
    }
    /**
     * Returns an instance of Tracker_TooltipFactory
     *
     * @return Tracker_TooltipFactory an instance of the factory
     */
    function getSemanticTooltipFactory() {
        return Tracker_TooltipFactory::instance();
    }

    /**
     * Returns an instance of Tracker_ContributorFactory
     *
     * @return Tracker_ContributorFactory an instance of the factory
     */
    function getSemanticContributorFactory() {
        return Tracker_Semantic_ContributorFactory::instance();
    }

    /**
     * Creates new Tracker_Semantic in the database
     *
     * @param Tracker_Semantic $semantic The semantic to save
     * @param Tracker          $tracker  The tracker
     *
     * @return bool true if the semantic is saved, false otherwise
     */
    public function saveObject($semantic, $tracker) {
        $semantic->setTracker($tracker);
        return $semantic->save();
    }

    /**
     * Duplicate the semantics from tracker source to tracker target
     *
     * @param int   $from_tracker_id The Id of the tracker source
     * @param int   $to_tracker_id   The Id of the tracker target
     * @param array $field_mapping   The mapping of the fields of the tracker
     *
     * @return void
     */
    public function duplicate($from_tracker_id, $to_tracker_id, $field_mapping) {
        foreach ($this->getSubFactories() as $factory) {
            $factory->duplicate($from_tracker_id, $to_tracker_id, $field_mapping);
        }
    }

    /** @return Tracker_Semantic_IRetrieveSemantic[] */
    private function getSubFactories() {
        $factories = array(
            $this->getSemanticTitleFactory(),
            $this->getSemanticDescriptionFactory(),
            $this->getSemanticStatusFactory(),
            $this->getSemanticContributorFactory(),
            $this->getSemanticTooltipFactory()
        );

        EventManager::instance()->processEvent(
            TRACKER_EVENT_GET_SEMANTIC_FACTORIES,
            array('factories' => &$factories)
        );

        return $factories;
    }
}
?>
