const express = require('express');
const router = express.Router();
const pesertaController = require('../controllers/pesertaController');

router.get('/', pesertaController.getAll);
router.get('/:id', pesertaController.getById);
router.post('/', pesertaController.create);
router.put('/:id', pesertaController.update);
router.delete('/:id', pesertaController.delete);

module.exports = router;