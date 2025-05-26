<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/NoteDao.php';

class NoteService extends BaseService {
    public function __construct()
    {
        $dao = new NoteDao();
        parent::__construct($dao);
    }

     // get note by id
    public function getNoteById($id)
    {
        $note = $this->getById($id);
        if (!$note) {
            throw new Exception("Note not found.");
        }
        return $note;
    }
    
    // create note
    public function createNote($data)
    {

        if (empty($data['name']) || empty($data['image'])) {
            throw new Exception("Missing required fields: name or image.");
        }

        // Use BaseService create method to insert note
        return $this->create($data);
    }
    
    // update note
    public function updateNote($id, $data)
    {
        $note = $this->getById($id);
        if (!$note) {
            throw new Exception("Note not found.");
        }
        return $this->update($id, $data);
    }

    // delete note
    public function deleteNote($id)
    {
        $note = $this->getById($id);
        if (!$note) {
            throw new Exception("Note not found.");
        }
        return $this->delete($id);
    }
}
