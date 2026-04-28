const express = require('express');
const cors = require('cors');
const db = require('./config/database');

// 1. IMPORT MODEL
const Peserta = require('./model/peserta');
const Provinsi = require('./model/provinsi'); 
const Kabkot = require('./model/kabkot'); 

// 2. DEFINISI RELASI
// Ini penting agar di tabel peserta bisa muncul nama Provinsi/Kota, bukan cuma ID nya saja.
Peserta.belongsTo(Provinsi, { foreignKey: 'provinsi_id', as: 'provinsi' });
Peserta.belongsTo(Kabkot, { foreignKey: 'kabkot_id', as: 'kabkot' });

const app = express();

// Gunakan cors tanpa batasan dulu untuk memastikan koneksi lancar
app.use(cors());
app.use(express.json());

// ==========================================
// 3. ROUTE UNTUK DROPDOWN (YANG TADI HILANG)
// ==========================================

// Ambil Semua Provinsi
app.get('/api/provinsi', async (req, res) => {
    try {
        const data = await Provinsi.findAll({ order: [['nama', 'ASC']] });
        res.json(data);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// Ambil Kabkot berdasarkan ID Provinsi
app.get('/api/kabko/provinsi/:id', async (req, res) => {
    try {
        const data = await Kabkot.findAll({ 
            where: { provinsi_id: req.params.id },
            order: [['nama', 'ASC']]
        });
        res.json(data);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// ==========================================
// 4. ROUTE PESERTA
// ==========================================

// Ambil Semua Peserta (Include Relasi)
app.get('/api/peserta', async (req, res) => {
    try {
        const data = await Peserta.findAll({
            include: [
                { model: Provinsi, as: 'provinsi' },
                { model: Kabkot, as: 'kabkot' }
            ],
            order: [['id', 'DESC']]
        });
        res.json(data);
    } catch (err) { 
        res.status(500).json({ error: err.message }); 
    }
});

// Create Peserta
app.post('/api/peserta', async (req, res) => {
    try {
        const baru = await Peserta.create(req.body);
        res.status(201).json(baru);
    } catch (err) { 
        res.status(400).json({ error: err.message }); 
    }
});

// Update Peserta
app.put('/api/peserta/:id', async (req, res) => {
    try {
        await Peserta.update(req.body, { 
            where: { id: req.params.id } 
        });
        res.json({ message: "Data terupdate!" });
    } catch (err) { 
        res.status(400).json({ error: err.message }); 
    }
});

// Delete Peserta
app.delete('/api/peserta/:id', async (req, res) => {
    try {
        await Peserta.destroy({ where: { id: req.params.id } });
        res.json({ message: "Data terhapus!" });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// Detail Peserta (Untuk Edit)
app.get('/api/peserta/:id', async (req, res) => {
    try {
        const data = await Peserta.findByPk(req.params.id, {
            include: [
                { model: Provinsi, as: 'provinsi' },
                { model: Kabkot, as: 'kabkot' }
            ]
        });
        res.json(data);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// ==========================================
// 5. JALANKAN SERVER
// ==========================================
db.sync({ alter: true })
    .then(() => {
        console.log('PostgreSQL Terhubung & Tabel Sinkron!');
        app.listen(5000, () => console.log('Backend jalan di http://localhost:5000'));
    })
    .catch(err => console.log('Gagal Terhubung Database: ' + err));