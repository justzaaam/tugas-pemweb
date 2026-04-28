const Peserta = require('../model/peserta');

const pesertaController = {
    // Tampil semua
    getAll: async (req, res) => {
        try {
            const data = await Peserta.findAll();
            res.json(data);
        } catch (err) {
            res.status(500).json({ error: err.message });
        }
    },

    // Tampil satu data
    getById: async (req, res) => {
        try {
            const data = await Peserta.findByPk(req.params.id);
            if (data) res.json(data);
            else res.status(404).json({ message: "Data tidak ditemukan" });
        } catch (err) {
            res.status(500).json({ error: err.message });
        }
    },

    // Tambah data
    create: async (req, res) => {
        try {
            const baru = await Peserta.create(req.body);
            res.status(201).json(baru);
        } catch (err) {
            res.status(400).json({ error: err.message });
        }
    },

    // Update data
    update: async (req, res) => {
        try {
            await Peserta.update(req.body, { where: { id: req.params.id } });
            res.json({ message: "Data berhasil diperbarui" });
        } catch (err) {
            res.status(400).json({ error: err.message });
        }
    },

    // Hapus data
    delete: async (req, res) => {
        try {
            await Peserta.destroy({ where: { id: req.params.id } });
            res.json({ message: "Data berhasil dihapus" });
        } catch (err) {
            res.status(500).json({ error: err.message });
        }
    }
};

module.exports = pesertaController;