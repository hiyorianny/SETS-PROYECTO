const request = require('supertest');
const express = require('express');
const anuncioRoutes = require('../../Backend/routes/anunciroutes.js');

const app = express();
app.use(express.json());
app.use('/api', anuncioRoutes);

describe('Anuncio Routes', () => {
  it('GET /api/anuncios debe responder con 200', async () => {
    const response = await request(app)
      .get('/api/anuncios')
      .expect(200);

    expect(response.body).toBeInstanceOf(Array);
  });
});