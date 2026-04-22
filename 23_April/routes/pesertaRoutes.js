const express = require('express');
const router = express.Router();
const Peserta = require('../model/peserta');

// GET ALL
router.get('/', async (req, res) => {
    try {
        const data = await Peserta.findAll();
        res.json(data);
    } catch (err) { res.status(500).json({ error: err.message }); }
});

// GET BY ID
router.get('/:id', async (req, res) => {
    try {
        const data = await Peserta.findByPk(req.params.id);
        if (data) res.json(data);
        else res.status(404).json({ message: "Tidak ditemukan" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

// POST (CREATE)
router.post('/', async (req, res) => {
    try {
        const baru = await Peserta.create(req.body);
        res.status(201).json(baru);
    } catch (err) { res.status(400).json({ error: err.message }); }
});

// PUT (UPDATE)
router.put('/:id', async (req, res) => {
    try {
        await Peserta.update(req.body, { where: { id: req.params.id } });
        res.json({ message: "Berhasil diupdate" });
    } catch (err) { res.status(400).json({ error: err.message }); }
});

// DELETE
router.delete('/:id', async (req, res) => {
    try {
        await Peserta.destroy({ where: { id: req.params.id } });
        res.json({ message: "Berhasil dihapus" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

module.exports = router;