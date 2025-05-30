const AnuncioController = require('../../Backend/controllers/controleranuncios.js');
const AnuncioModel = require('../../Backend/models/anuncio');

jest.mock('../../Backend/models/anuncio');

describe('AnuncioController', () => {
  let mockReq, mockRes;

  beforeEach(() => {
    mockReq = { body: {}, params: {} };
    mockRes = {
      status: jest.fn().mockReturnThis(),
      json: jest.fn()
    };
  });

  describe('getAll', () => {
    it('debe manejar errores correctamente', async () => {
      const mockError = new Error('DB Error');
      AnuncioModel.getAll.mockImplementation(callback => callback(mockError, null));

      await AnuncioController.getAll(mockReq, mockRes);

      expect(mockRes.status).toHaveBeenCalledWith(500);
      expect(mockRes.json).toHaveBeenCalledWith({ error: 'DB Error' });
    });
  });
});