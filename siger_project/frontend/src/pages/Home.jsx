import { useState, useEffect } from 'react'
import axios from 'axios'
import './Home.css'

function Home() {
  const [menu, setMenu] = useState([])
  const [loading, setLoading] = useState(true)
  const [category, setCategory] = useState('all')

  useEffect(() => {
    loadMenu()
  }, [category])

  const loadMenu = async () => {
    try {
      const url = category === 'all' 
        ? '/api/controllers/get_menu.php'
        : `/api/controllers/get_menu.php?category=${category}`
      
      const response = await axios.get(url)
      
      if (response.data.success) {
        setMenu(response.data.data)
      }
      setLoading(false)
    } catch (error) {
      console.error('Error loading menu:', error)
      setLoading(false)
    }
  }

  const categories = ['all', 'entradas', 'principales', 'postres', 'bebidas']

  return (
    <div className="home">
      <section className="hero">
        <div className="hero-content">
          <h2>Bienvenido a SIGER</h2>
          <p>Disfruta de la mejor experiencia gastronómica</p>
          <div className="hero-buttons">
            <button className="btn btn-primary">Hacer Reserva</button>
            <button className="btn btn-secondary">Ver Menú</button>
          </div>
        </div>
      </section>

      <section className="menu-section">
        <div className="container">
          <h2>Nuestro Menú</h2>
          
          <div className="menu-filters">
            {categories.map(cat => (
              <button
                key={cat}
                className={`filter-btn ${category === cat ? 'active' : ''}`}
                onClick={() => setCategory(cat)}
              >
                {cat === 'all' ? 'Todos' : cat.charAt(0).toUpperCase() + cat.slice(1)}
              </button>
            ))}
          </div>

          {loading ? (
            <div className="loading">Cargando menú...</div>
          ) : (
            <div className="menu-grid">
              {menu.map(item => (
                <div key={item.id} className="menu-item">
                  <img 
                    src={item.image || '/placeholder.jpg'} 
                    alt={item.name}
                    onError={(e) => e.target.src = '/placeholder.jpg'}
                  />
                  <div className="menu-item-info">
                    <h3>{item.name}</h3>
                    <p>{item.description || 'Delicioso platillo de nuestra carta'}</p>
                    <div className="menu-item-price">${item.price.toFixed(2)}</div>
                    {item.available ? (
                      <button className="btn btn-primary">Agregar</button>
                    ) : (
                      <span className="badge">No disponible</span>
                    )}
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </section>

      <section className="contact-section">
        <div className="container">
          <h2>Contáctanos</h2>
          <div className="contact-info">
            <div className="info-card">
              <h3>📍 Dirección</h3>
              <p>Av. Principal #123, Ciudad</p>
            </div>
            <div className="info-card">
              <h3>📞 Teléfono</h3>
              <p>(123) 456-7890</p>
            </div>
            <div className="info-card">
              <h3>🕐 Horario</h3>
              <p>Lun - Dom: 12:00 PM - 11:00 PM</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  )
}

export default Home
