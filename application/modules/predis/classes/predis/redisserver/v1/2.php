<?php

class Predis_RedisServer_v1_2 extends Predis_RedisServerProfile {
    public function getVersion() { return '1.2'; }
    public function getSupportedCommands() {
        return array(
            /* miscellaneous commands */
            'ping'      => 'Predis_Commands_Ping',
            'echo'      => 'Predis_Commands_DoEcho',
            'auth'      => 'Predis_Commands_Auth',

            /* connection handling */
            'quit'      => 'Predis_Commands_Quit',

            /* commands operating on string values */
            'set'                     => 'Predis_Commands_Set',
            'setnx'                   => 'Predis_Commands_SetPreserve',
                'setPreserve'         => 'Predis_Commands_SetPreserve',
            'mset'                    => 'Predis_Commands_SetMultiple',
                'setMultiple'         => 'Predis_Commands_SetMultiple',
            'msetnx'                  => 'Predis_Commands_SetMultiplePreserve',
                'setMultiplePreserve' => 'Predis_Commands_SetMultiplePreserve',
            'get'                     => 'Predis_Commands_Get',
            'mget'                    => 'Predis_Commands_GetMultiple',
                'getMultiple'         => 'Predis_Commands_GetMultiple',
            'getset'                  => 'Predis_Commands_GetSet',
                'getSet'              => 'Predis_Commands_GetSet',
            'incr'                    => 'Predis_Commands_Increment',
                'increment'           => 'Predis_Commands_Increment',
            'incrby'                  => 'Predis_Commands_IncrementBy',
                'incrementBy'         => 'Predis_Commands_IncrementBy',
            'decr'                    => 'Predis_Commands_Decrement',
                'decrement'           => 'Predis_Commands_Decrement',
            'decrby'                  => 'Predis_Commands_DecrementBy',
                'decrementBy'         => 'Predis_Commands_DecrementBy',
            'exists'                  => 'Predis_Commands_Exists',
            'del'                     => 'Predis_Commands_Delete',
                'delete'              => 'Predis_Commands_Delete',
            'type'                    => 'Predis_Commands_Type',

            /* commands operating on the key space */
            'keys'               => 'Predis_Commands_Keys',
            'randomkey'          => 'Predis_Commands_RandomKey',
                'randomKey'      => 'Predis_Commands_RandomKey',
            'rename'             => 'Predis_Commands_Rename',
            'renamenx'           => 'Predis_Commands_RenamePreserve',
                'renamePreserve' => 'Predis_Commands_RenamePreserve',
            'expire'             => 'Predis_Commands_Expire',
            'expireat'           => 'Predis_Commands_ExpireAt',
                'expireAt'       => 'Predis_Commands_ExpireAt',
            'dbsize'             => 'Predis_Commands_DatabaseSize',
                'databaseSize'   => 'Predis_Commands_DatabaseSize',
            'ttl'                => 'Predis_Commands_TimeToLive',
                'timeToLive'     => 'Predis_Commands_TimeToLive',

            /* commands operating on lists */
            'rpush'            => 'Predis_Commands_ListPushTail',
                'pushTail'     => 'Predis_Commands_ListPushTail',
            'lpush'            => 'Predis_Commands_ListPushHead',
                'pushHead'     => 'Predis_Commands_ListPushHead',
            'llen'             => 'Predis_Commands_ListLength',
                'listLength'   => 'Predis_Commands_ListLength',
            'lrange'           => 'Predis_Commands_ListRange',
                'listRange'    => 'Predis_Commands_ListRange',
            'ltrim'            => 'Predis_Commands_ListTrim',
                'listTrim'     => 'Predis_Commands_ListTrim',
            'lindex'           => 'Predis_Commands_ListIndex',
                'listIndex'    => 'Predis_Commands_ListIndex',
            'lset'             => 'Predis_Commands_ListSet',
                'listSet'      => 'Predis_Commands_ListSet',
            'lrem'             => 'Predis_Commands_ListRemove',
                'listRemove'   => 'Predis_Commands_ListRemove',
            'lpop'             => 'Predis_Commands_ListPopFirst',
                'popFirst'     => 'Predis_Commands_ListPopFirst',
            'rpop'             => 'Predis_Commands_ListPopLast',
                'popLast'      => 'Predis_Commands_ListPopLast',
            'rpoplpush'        => 'Predis_Commands_ListPopLastPushHead',
                'listPopLastPushHead'  => 'Predis_Commands_ListPopLastPushHead',

            /* commands operating on sets */
            'sadd'                      => 'Predis_Commands_SetAdd',
                'setAdd'                => 'Predis_Commands_SetAdd',
            'srem'                      => 'Predis_Commands_SetRemove',
                'setRemove'             => 'Predis_Commands_SetRemove',
            'spop'                      => 'Predis_Commands_SetPop',
                'setPop'                => 'Predis_Commands_SetPop',
            'smove'                     => 'Predis_Commands_SetMove',
                'setMove'               => 'Predis_Commands_SetMove',
            'scard'                     => 'Predis_Commands_SetCardinality',
                'setCardinality'        => 'Predis_Commands_SetCardinality',
            'sismember'                 => 'Predis_Commands_SetIsMember',
                'setIsMember'           => 'Predis_Commands_SetIsMember',
            'sinter'                    => 'Predis_Commands_SetIntersection',
                'setIntersection'       => 'Predis_Commands_SetIntersection',
            'sinterstore'               => 'Predis_Commands_SetIntersectionStore',
                'setIntersectionStore'  => 'Predis_Commands_SetIntersectionStore',
            'sunion'                    => 'Predis_Commands_SetUnion',
                'setUnion'              => 'Predis_Commands_SetUnion',
            'sunionstore'               => 'Predis_Commands_SetUnionStore',
                'setUnionStore'         => 'Predis_Commands_SetUnionStore',
            'sdiff'                     => 'Predis_Commands_SetDifference',
                'setDifference'         => 'Predis_Commands_SetDifference',
            'sdiffstore'                => 'Predis_Commands_SetDifferenceStore',
                'setDifferenceStore'    => 'Predis_Commands_SetDifferenceStore',
            'smembers'                  => 'Predis_Commands_SetMembers',
                'setMembers'            => 'Predis_Commands_SetMembers',
            'srandmember'               => 'Predis_Commands_SetRandomMember',
                'setRandomMember'       => 'Predis_Commands_SetRandomMember',

            /* commands operating on sorted sets */
            'zadd'                          => 'Predis_Commands_ZSetAdd',
                'zsetAdd'                   => 'Predis_Commands_ZSetAdd',
            'zincrby'                       => 'Predis_Commands_ZSetIncrementBy',
                'zsetIncrementBy'           => 'Predis_Commands_ZSetIncrementBy',
            'zrem'                          => 'Predis_Commands_ZSetRemove',
                'zsetRemove'                => 'Predis_Commands_ZSetRemove',
            'zrange'                        => 'Predis_Commands_ZSetRange',
                'zsetRange'                 => 'Predis_Commands_ZSetRange',
            'zrevrange'                     => 'Predis_Commands_ZSetReverseRange',
                'zsetReverseRange'          => 'Predis_Commands_ZSetReverseRange',
            'zrangebyscore'                 => 'Predis_Commands_ZSetRangeByScore',
                'zsetRangeByScore'          => 'Predis_Commands_ZSetRangeByScore',
            'zcard'                         => 'Predis_Commands_ZSetCardinality',
                'zsetCardinality'           => 'Predis_Commands_ZSetCardinality',
            'zscore'                        => 'Predis_Commands_ZSetScore',
                'zsetScore'                 => 'Predis_Commands_ZSetScore',
            'zremrangebyscore'              => 'Predis_Commands_ZSetRemoveRangeByScore',
                'zsetRemoveRangeByScore'    => 'Predis_Commands_ZSetRemoveRangeByScore',

            /* multiple databases handling commands */
            'select'                => 'Predis_Commands_SelectDatabase',
                'selectDatabase'    => 'Predis_Commands_SelectDatabase',
            'move'                  => 'Predis_Commands_MoveKey',
                'moveKey'           => 'Predis_Commands_MoveKey',
            'flushdb'               => 'Predis_Commands_FlushDatabase',
                'flushDatabase'     => 'Predis_Commands_FlushDatabase',
            'flushall'              => 'Predis_Commands_FlushAll',
                'flushDatabases'    => 'Predis_Commands_FlushAll',

            /* sorting */
            'sort'                  => 'Predis_Commands_Sort',

            /* remote server control commands */
            'info'                  => 'Predis_Commands_Info',
            'slaveof'               => 'Predis_Commands_SlaveOf',
                'slaveOf'           => 'Predis_Commands_SlaveOf',

            /* persistence control commands */
            'save'                  => 'Predis_Commands_Save',
            'bgsave'                => 'Predis_Commands_BackgroundSave',
                'backgroundSave'    => 'Predis_Commands_BackgroundSave',
            'lastsave'              => 'Predis_Commands_LastSave',
                'lastSave'          => 'Predis_Commands_LastSave',
            'shutdown'              => 'Predis_Commands_Shutdown',
            'bgrewriteaof'                      =>  'Predis_Commands_BackgroundRewriteAppendOnlyFile',
            'backgroundRewriteAppendOnlyFile'   =>  'Predis_Commands_BackgroundRewriteAppendOnlyFile',
        );
    }
}

?>
