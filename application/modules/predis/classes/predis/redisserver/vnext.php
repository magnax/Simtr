<?php

class Predis_RedisServer_vNext extends Predis_RedisServer_v2_0 {
    public function getVersion() { return '2.1'; }
    public function getSupportedCommands() {
        return array_merge(parent::getSupportedCommands(), array(
            /* transactions */
            'watch'                     => 'Predis_Commands_Watch',
            'unwatch'                   => 'Predis_Commands_Unwatch',

            /* commands operating on string values */
            'strlen'                    => 'Predis_Commands_Strlen',

            /* commands operating on the key space */
            'persist'                   => 'Predis_Commands_Persist',

            /* commands operating on lists */
            'rpushx'                    => 'Predis_Commands_ListPushTailX',
            'lpushx'                    => 'Predis_Commands_ListPushHeadX',
            'linsert'                   => 'Predis_Commands_ListInsert',

            /* commands operating on sorted sets */
            'zrevrangebyscore'          => 'Predis_Commands_ZSetReverseRangeByScore',
        ));
    }
}

?>
