const Login = () => import('~/pages/auth/login').then(m => m.default || m)
const Register = () => import('~/pages/auth/register').then(m => m.default || m)
const PasswordEmail = () => import('~/pages/auth/password/email').then(m => m.default || m)
const PasswordReset = () => import('~/pages/auth/password/reset').then(m => m.default || m)
const NotFound = () => import('~/pages/errors/404').then(m => m.default || m)

const CampsSearch = () => import('~/pages/camps/search').then(m => m.default || m)
const CampsSearchResults = () => import('~/pages/camps/results').then(m => m.default || m)
const CampsDetails = () => import('~/pages/camps/details').then(m => m.default || m)
const CampsParent1 = () => import('~/pages/camps/parent1').then(m => m.default || m)
const CampsParent2 = () => import('~/pages/camps/parent2').then(m => m.default || m)
const CampsChild = () => import('~/pages/camps/child').then(m => m.default || m)
const CampsChildren = () => import('~/pages/camps/children').then(m => m.default || m)
const CampsSelect = () => import('~/pages/camps/select').then(m => m.default || m)
const CampsPayment = () => import('~/pages/camps/payment').then(m => m.default || m)
const CampsSuccess = () => import('~/pages/camps/success').then(m => m.default || m)

export default [
  { path: '/camps', name: 'camps.search', component: CampsSearch },
  { path: '/camps/results', name: 'camps.results', component: CampsSearchResults },
  { path: '/camps/details', name: 'camps.details', component: CampsDetails },
  { path: '/camps/register', name: 'camps.register', redirect: { name: 'camps.register.parent1' } },
  { path: '/camps/register/parent1', name: 'camps.register.parent1', component: CampsParent1 },
  { path: '/camps/register/parent2', name: 'camps.register.parent2', component: CampsParent2 },
  { path: '/camps/child-details', name: 'camps.child-details', component: CampsChild },
  { path: '/camps/all-children', name: 'camps.all-children', component: CampsChildren },
  { path: '/camps/select', name: 'camps.select', component: CampsSelect },
  { path: '/camps/payment', name: 'camps.payment', component: CampsPayment },
  { path: '/camps/success', name: 'camps.success', component: CampsSuccess },

  { path: '/camps/login', name: 'camps.login', component: Login },
  { path: '/camps/create-account', name: 'camps.create-account', component: Register },
  { path: '/camps/password/reset', name: 'camps.password.request', component: PasswordEmail },
  { path: '/camps/password/reset/:token', name: 'camps.password.reset', component: PasswordReset },

  { path: '*', component: NotFound }
]
