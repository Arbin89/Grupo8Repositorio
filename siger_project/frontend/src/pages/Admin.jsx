function Admin({ user }) {
  if (!user || user.role !== 'admin') {
    return (
      <div className="container">
        <h2>Acceso Restringido</h2>
        <p>Debes iniciar sesión como administrador para acceder a esta sección.</p>
      </div>
    )
  }

  return (
    <div className="container">
      <h1>Panel de Administración</h1>
      <p>Bienvenido, {user.full_name}</p>
      
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))', gap: '2rem', marginTop: '2rem' }}>
        <div style={{ background: 'white', padding: '2rem', borderRadius: '15px', boxShadow: '0 4px 6px rgba(0,0,0,0.1)' }}>
          <h3>👥 Usuarios</h3>
          <p>Gestionar usuarios del sistema</p>
        </div>
        <div style={{ background: 'white', padding: '2rem', borderRadius: '15px', boxShadow: '0 4px 6px rgba(0,0,0,0.1)' }}>
          <h3>🍽️ Inventario</h3>
          <p>Gestionar productos y menú</p>
        </div>
        <div style={{ background: 'white', padding: '2rem', borderRadius: '15px', boxShadow: '0 4px 6px rgba(0,0,0,0.1)' }}>
          <h3>📊 Reportes</h3>
          <p>Ver estadísticas de ventas</p>
        </div>
        <div style={{ background: 'white', padding: '2rem', borderRadius: '15px', boxShadow: '0 4px 6px rgba(0,0,0,0.1)' }}>
          <h3>📅 Reservas</h3>
          <p>Gestionar reservas</p>
        </div>
      </div>
    </div>
  )
}

export default Admin
