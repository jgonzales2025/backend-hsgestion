<?php

namespace App\Modules\EntryGuideArticle\Domain\Interface;

use App\Modules\EntryGuideArticle\Domain\Entities\EntryGuideArticle;

interface EntryGuideArticleRepositoryInterface{

      public function save(EntryGuideArticle $entryGuideArticle ):?EntryGuideArticle;
      public function findAll():array;
      public function findById(int $id):array;
      public function deleteByEntryGuideId(int $id):void;



}
