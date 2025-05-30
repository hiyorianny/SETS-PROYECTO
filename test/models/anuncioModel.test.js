const AnuncioModel = require('../../Backend/models/anuncio.js');
const db = require('../../Backend/db/db.js');

jest.mock('../../Backend/db/db.js');

describe('AnuncioModel', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  describe('getAll', () => {
    it('debe retornar anuncios ordenados', async () => {
      const mockAnuncios = [{ id: 1, titulo: 'Test' }];
      db.query.mockImplementation((query, callback) => {
        callback(null, mockAnuncios);
      });

      await new Promise(done => {
        AnuncioModel.getAll((err, results) => {
          expect(err).toBeNull();
          expect(results).toEqual(mockAnuncios);
          done();
        });
      });
    });
  });
});