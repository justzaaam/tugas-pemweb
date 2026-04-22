const express = require('express');
const cors = require('cors');
const db = require('./config/database');
const Peserta = require('./model/peserta');

const app = express();
app.use(cors());
app.use(express.json());

// 1. READ ALL (Tampil Semua)
app.get('/api/peserta', async (req, res) => {
    const data = await Peserta.findAll();
    res.json(data);
});

// 2. READ BY ID (Cari satu data)
app.get('/api/peserta/:id', async (req, res) => {
    const data = await Peserta.findByPk(req.params.id);
    if (data) res.json(data);
    else res.status(404).json({ message: "Data tidak ketemu" });
});

// 3. CREATE (Simpan Data)
app.post('/api/peserta', async (req, res) => {
    try {
        const baru = await Peserta.create(req.body);
        res.status(201).json(baru);
    } catch (err) { res.status(400).json({ error: err.message }); }
});

// 4. UPDATE (Ubah Data)
app.put('/api/peserta/:id', async (req, res) => {
    await Peserta.update(req.body, { where: { id: req.params.id } });
    res.json({ message: "Data terupdate!" });
});

// 5. DELETE (Hapus Data)
app.delete('/api/peserta/:id', async (req, res) => {
    await Peserta.destroy({ where: { id: req.params.id } });
    res.json({ message: "Data terhapus!" });
});

db.authenticate()
    .then(() => {
        console.log('PostgreSQL Terhubung!');
        app.listen(5000, () => console.log('Server aktif di http://localhost:5000'));
    })
    .catch(err => console.log('Gagal: ' + err));