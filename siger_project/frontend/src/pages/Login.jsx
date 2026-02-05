import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import axios from 'axios'
import './Login.css'

function Login({ setUser }) {
  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError('')
    setLoading(true)

    try {
      const response = await axios.post('/api/controllers/auth.php?action=login', {
        username,
        password
      })

      if (response.data.success) {
        setUser(response.data.data.user)
        navigate(response.data.data.redirect)
      } else {
        setError(response.data.message)
      }
    } catch (err) {
      setError('Error al conectar con el servidor')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="login-page">
      <div className="login-container">
        <h2>🍽️ Iniciar Sesión</h2>
        <p>Sistema de Gestión de Restaurante</p>

        {error && <div className="error">{error}</div>}

        <form onSubmit={handleSubmit} className="login-form">
          <div className="form-group">
            <label>Usuario</label>
            <input
              type="text"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              required
              disabled={loading}
            />
          </div>

          <div className="form-group">
            <label>Contraseña</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              disabled={loading}
            />
          </div>

          <button type="submit" className="btn btn-primary" disabled={loading}>
            {loading ? 'Ingresando...' : 'Ingresar'}
          </button>
        </form>

        <div className="login-info">
          <p><strong>Credenciales de prueba:</strong></p>
          <ul>
            <li>Admin: admin / admin123</li>
            <li>Cocina: cocina / cocina123</li>
            <li>Mesero: mesero / mesero123</li>
          </ul>
        </div>
      </div>
    </div>
  )
}

export default Login
