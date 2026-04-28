const { DataTypes } = require('sequelize');
const db = require('../config/database');

const Kabkot = db.define('kabkot', {
    id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
    // Kita beri tahu Sequelize: "Tolong ambil dari kolom 'nama_kabkot' ya"
    nama: { 
        type: DataTypes.STRING,
        field: 'nama_kabkot' 
    },
    provinsi_id: { type: DataTypes.INTEGER }
}, {
    tableName: 'kabkot', // Kita pakai tabel 'kabkot' (pakai 't' di belakang) karena di situ datanya tersimpan
    timestamps: false
});

module.exports = Kabkot;