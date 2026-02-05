import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom'
import { useState } from 'react'
import './App.css'
import Home from './pages/Home'
import Admin from './pages/Admin'
import Kitchen from './pages/Kitchen'
import Tablet from './pages/Tablet'
import Login from './pages/Login'

function App() {
  const [user, setUser] = useState(null)

  return (
    <Router>
      <div className="app">
        <nav className="main-nav">
          <div className="nav-container">
            <Link to="/" className="logo">
              <h1>🍽️ SIGER</h1>
            </Link>
            <div className="nav-links">
              <Link to="/">Home</Link>
              <Link to="/tablet">Tablet</Link>
              <Link to="/kitchen">Cocina</Link>
              <Link to="/admin">Admin</Link>
              {!user && <Link to="/login" className="btn-login">Login</Link>}
            </div>
          </div>
        </nav>

        <main className="main-content">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/login" element={<Login setUser={setUser} />} />
            <Route path="/admin/*" element={<Admin user={user} />} />
            <Route path="/kitchen" element={<Kitchen user={user} />} />
            <Route path="/tablet" element={<Tablet />} />
          </Routes>
        </main>

        <footer className="footer">
          <p>&copy; 2026 SIGER - Sistema de Gestión de Restaurante</p>
        </footer>
      </div>
    </Router>
  )
}

export default App
