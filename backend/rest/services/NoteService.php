<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/NoteDao.php';

class NoteService extends BaseService
{
    public function __construct()
    {
        $dao = new NoteDao();

        parent::__construct($dao);     // calling parent constructor to make sure $dao is set and core logic is inherited
    }

    public function getAllNotes()
    {
        return $this->dao->getAll();   // gets all notes from database
    }

    public function getNoteById($id)
    {
        $note = $this->dao->getById($id);    // gets a specific note by id
        if (!$note) {
            throw new Exception("Note not found.");
        }
        return $note;
    }

    public function addNote($noteData)
    {
        if (empty($noteData['name'])) {
            throw new Exception("Note name is required.");
        }

        return $this->dao->insert($noteData);     // adding a note
    }

    public function updateNote($id, $noteData)
    {
        $note = $this->dao->getById($id);
        if (!$note) {
            throw new Exception("Note not found.");
        }

        if (empty($noteData['name'])) {
            throw new Exception("Note name is required.");
        }

        return $this->dao->update($id, $noteData);    // updating a note
    }

    public function deleteNote($id)
    {
        $note = $this->dao->getById($id);
        if (!$note) {
            throw new Exception("Note not found.");
        }

        return $this->dao->delete($id);     // deleting a note
    }
}
