<?php

interface LessonRepositoryInterface
{
    /**
     * Fetch all records.
     *
     * @return array
     */
    public function getAll();
}


class FileLessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        // return through file system
        return [];
    }
}


class DbLessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        /*
            Violates LSP because:
              - the return type is different
              - the consumer of this subclass and FileLessonRepository won't work identically
         */
         //return Lesson::all();

        // to fix this
        return Lesson::all()->toArray();
    }
}