const db = require('../db/db');

class TorreModel {
    static getAll(callback) {
        const query = `
            SELECT numApartamento, pisos, torre 
            FROM apartamento 
            ORDER BY torre, pisos, numApartamento
        `;

        db.query(query, (err, results) => {
            if (err) return callback(err, null);
            

            const torres = {};
            results.forEach(item => {
                const torre = item.torre;
                const piso = item.pisos;

                if (!torres[torre]) {
                    torres[torre] = {};
                }

                if (!torres[torre][piso]) {
                    torres[torre][piso] = [];
                }

                torres[torre][piso].push({
                    numApartamento: item.numApartamento
                });
            });

            callback(null, {
                torres: torres,
                torresList: Object.keys(torres).sort()
            });
        });
    }
}

module.exports = TorreModel;
