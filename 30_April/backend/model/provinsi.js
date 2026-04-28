const { DataTypes } = require('sequelize');
const db = require('../config/database');

const Provinsi = db.define('provinsi', {
    id: { type: DataTypes.INTEGER, primaryKey: true, autoIncrement: true },
    // Kita beri tahu Sequelize: "Namanya di kode adalah 'nama', tapi tolong ambil dari kolom 'nama_provinsi' di database"
    nama: { 
        type: DataTypes.STRING,
        field: 'nama_provinsi' 
    }
}, {
    tableName: 'provinsi', // Tabelnya benar 'provinsi'
    timestamps: false
});

module.exports = Provinsi;