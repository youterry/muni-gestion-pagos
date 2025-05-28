const routes = [
  {
    path: '/',
    component: () => import('layouts/AdminLayout.vue'), // Tu layout principal
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') }, // Tu página de inicio dentro del layout
      {
        path: '/curso', // Ruta para acceder a Cursos
        name: 'Curso',
        component: () => import('pages/Cursos/CursosList.vue')
      },
      {
        path: '/pagos', // ¡Añade esta ruta para Pagos!
        name: 'Pagos',  // Un nombre para la ruta (opcional pero bueno para referencias)
        component: () => import('pages/Pagos/PagosList.vue') // Ruta a tu componente PagosList
      },
    ]
  },

  // Siempre deja esto como último,
  // pero también puedes eliminarlo
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
