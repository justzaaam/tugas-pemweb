const { DataTypes } = require('sequelize');
const db = require('../config/database');

const Peserta = db.define('peserta', {
    nama: { type: DataTypes.STRING },
    tempatlahir: { type: DataTypes.STRING },
    tanggallahir: { type: DataTypes.DATEONLY },
    agama: { type: DataTypes.STRING },
    alamat: { type: DataTypes.TEXT },
    telepon: { type: DataTypes.STRING },
    jk: { type: DataTypes.INTEGER }, // 1=Laki, 0=Perempuan
    hobi: { type: DataTypes.STRING },
    provinsi_id: { type: DataTypes.INTEGER },
    kabkot_id: { type: DataTypes.INTEGER }
}, { tableName: 'peserta', timestamps: false });

module.exports = Peserta;