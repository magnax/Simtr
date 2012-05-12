<?php

class Predis_RedisServer_v2_0 extends Predis_RedisServer_v1_2 {
    public function getVersion() { return '2.0'; }
    public function getSupportedCommands() {
        return array_merge(parent::getSupportedCommands(), array(
            /* transactions */
            'multi'                     => 'Predis_Commands_Multi',
            'exec'                      => 'Predis_Commands_Exec',
            'discard'                   => 'Predis_Commands_Discard',

            /* commands operating on string values */
            'setex'                     => 'Predis_Commands_SetExpire',
                'setExpire'             => 'Predis_Commands_SetExpire',
            'append'                    => 'Predis_Commands_Append',
            'substr'                    => 'Predis_Commands_Substr',

            /* commands operating on lists */
            'blpop'                     => 'Predis_Commands_ListPopFirstBlocking',
                'popFirstBlocking'      => 'Predis_Commands_ListPopFirstBlocking',
            'brpop'                     => 'Predis_Commands_ListPopLastBlocking',
                'popLastBlocking'       => 'Predis_Commands_ListPopLastBlocking',

            /* commands operating on sorted sets */
            'zunionstore'               => 'Predis_Commands_ZSetUnionStore',
                'zsetUnionStore'        => 'Predis_Commands_ZSetUnionStore',
            'zinterstore'               => 'Predis_Commands_ZSetIntersectionStore',
                'zsetIntersectionStore' => 'Predis_Commands_ZSetIntersectionStore',
            'zcount'                    => 'Predis_Commands_ZSetCount',
                'zsetCount'             => 'Predis_Commands_ZSetCount',
            'zrank'                     => 'Predis_Commands_ZSetRank',
                'zsetRank'              => 'Predis_Commands_ZSetRank',
            'zrevrank'                  => 'Predis_Commands_ZSetReverseRank',
                'zsetReverseRank'       => 'Predis_Commands_ZSetReverseRank',
            'zremrangebyrank'           => 'Predis_Commands_ZSetRemoveRangeByRank',
                'zsetRemoveRangeByRank' => 'Predis_Commands_ZSetRemoveRangeByRank',

            /* commands operating on hashes */
            'hset'                      => 'Predis_Commands_HashSet',
                'hashSet'               => 'Predis_Commands_HashSet',
            'hsetnx'                    => 'Predis_Commands_HashSetPreserve',
                'hashSetPreserve'       => 'Predis_Commands_HashSetPreserve',
            'hmset'                     => 'Predis_Commands_HashSetMultiple',
                'hashSetMultiple'       => 'Predis_Commands_HashSetMultiple',
            'hincrby'                   => 'Predis_Commands_HashIncrementBy',
                'hashIncrementBy'       => 'Predis_Commands_HashIncrementBy',
            'hget'                      => 'Predis_Commands_HashGet',
                'hashGet'               => 'Predis_Commands_HashGet',
            'hmget'                     => 'Predis_Commands_HashGetMultiple',
                'hashGetMultiple'       => 'Predis_Commands_HashGetMultiple',
            'hdel'                      => 'Predis_Commands_HashDelete',
                'hashDelete'            => 'Predis_Commands_HashDelete',
            'hexists'                   => 'Predis_Commands_HashExists',
                'hashExists'            => 'Predis_Commands_HashExists',
            'hlen'                      => 'Predis_Commands_HashLength',
                'hashLength'            => 'Predis_Commands_HashLength',
            'hkeys'                     => 'Predis_Commands_HashKeys',
                'hashKeys'              => 'Predis_Commands_HashKeys',
            'hvals'                     => 'Predis_Commands_HashValues',
                'hashValues'            => 'Predis_Commands_HashValues',
            'hgetall'                   => 'Predis_Commands_HashGetAll',
                'hashGetAll'            => 'Predis_Commands_HashGetAll',

            /* publish - subscribe */
            'subscribe'                 => 'Predis_Commands_Subscribe',
            'unsubscribe'               => 'Predis_Commands_Unsubscribe',
            'psubscribe'                => 'Predis_Commands_SubscribeByPattern',
            'punsubscribe'              => 'Predis_Commands_UnsubscribeByPattern',
            'publish'                   => 'Predis_Commands_Publish',

            /* remote server control commands */
            'config'                    => 'Predis_Commands_Config',
                'configuration'         => 'Predis_Commands_Config',
        ));
    }
}

?>
