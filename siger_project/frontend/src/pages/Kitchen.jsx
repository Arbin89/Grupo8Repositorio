import { useState, useEffect } from 'react'
import axios from 'axios'
import './Kitchen.css'

function Kitchen({ user }) {
  const [orders, setOrders] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    loadOrders()
    const interval = setInterval(loadOrders, 5000) // Actualizar cada 5 segundos
    return () => clearInterval(interval)
  }, [])

  const loadOrders = async () => {
    try {
      const response = await axios.get('/api/controllers/kitchen_sync.php')
      if (response.data.success) {
        setOrders(response.data.data)
      }
      setLoading(false)
    } catch (error) {
      console.error('Error loading orders:', error)
      setLoading(false)
    }
  }

  const updateOrderStatus = async (orderId, newStatus) => {
    try {
      const response = await axios.post('/api/controllers/kitchen_sync.php', {
        order_id: orderId,
        status: newStatus
      })

      if (response.data.success) {
        loadOrders()
      }
    } catch (error) {
      console.error('Error updating order:', error)
    }
  }

  const getStatusColor = (status) => {
    const colors = {
      pending: '#ffc107',
      preparing: '#17a2b8',
      ready: '#28a745',
      delivered: '#6c757d'
    }
    return colors[status] || '#666'
  }

  if (loading) {
    return <div className="loading">Cargando órdenes...</div>
  }

  return (
    <div className="kitchen-page">
      <div className="kitchen-header">
        <h1>👨‍🍳 Pantalla de Cocina</h1>
        <p>Órdenes Activas: {orders.length}</p>
      </div>

      <div className="orders-grid">
        {orders.length === 0 ? (
          <div className="no-orders">
            <h2>✅ No hay órdenes pendientes</h2>
            <p>Todos los pedidos han sido completados</p>
          </div>
        ) : (
          orders.map(order => (
            <div key={order.id} className="order-card" style={{ borderLeft: `5px solid ${getStatusColor(order.status)}` }}>
              <div className="order-header">
                <div>
                  <h3>Orden #{order.id}</h3>
                  <p className="table-number">Mesa: {order.table_number || 'Para llevar'}</p>
                </div>
                <div className="order-time">
                  <p>{order.elapsed_minutes} min</p>
                  {order.is_delayed && <span className="delayed-badge">⚠️ Retrasada</span>}
                </div>
              </div>

              <div className="order-items">
                <p><strong>Artículos:</strong></p>
                <p>{order.items_summary}</p>
              </div>

              <div className="order-actions">
                {order.status === 'pending' && (
                  <button
                    className="btn btn-info"
                    onClick={() => updateOrderStatus(order.id, 'preparing')}
                  >
                    Iniciar Preparación
                  </button>
                )}
                {order.status === 'preparing' && (
                  <button
                    className="btn btn-success"
                    onClick={() => updateOrderStatus(order.id, 'ready')}
                  >
                    Marcar Como Listo
                  </button>
                )}
              </div>

              <div className="order-status">
                Estado: <span style={{ color: getStatusColor(order.status) }}>
                  {order.status === 'pending' && '⏳ Pendiente'}
                  {order.status === 'preparing' && '🔥 En Preparación'}
                  {order.status === 'ready' && '✅ Listo'}
                </span>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  )
}

export default Kitchen
