<?php

namespace App\ApiGen;

use ApiGen\Index\Index;
use ApiGen\Indexer;

class CustomIndexer extends Indexer
{
    public function postProcess(Index $index): void
    {
        // DAG
        $this->indexDirectedAcyclicGraph($index);

        // instance of
        foreach ([$index->class, $index->interface, $index->enum] as $infos) {
            foreach ($infos as $info) {
                $this->indexInstanceOf($index, $info);
            }
        }

        // exceptions
        foreach ($index->namespace as $namespaceIndex) {
            foreach ($namespaceIndex->class as $info) {
                if ($info->isThrowable($index)) {
                    unset($namespaceIndex->class[$info->name->shortLower]);
                    $namespaceIndex->exception[$info->name->shortLower] = $info;
                }
            }
        }

        // method overrides & implements
        // TODO: PR this to ApiGen
        foreach ($index->classExtends[''] ?? [] as $info) {
            $this->indexClassMethodOverrides($index, $info, [], []);
        }

        foreach ($index->enum as $info) {
            $this->indexClassMethodOverrides($index, $info, [], []);
        }

        // sort
        $this->sort($index);
    }

}
