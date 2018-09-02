const Welcome = () => import('~/pages/welcome').then(m => m.default || m)
const Login = () => import('~/pages/auth/login').then(m => m.default || m)
const Register = () => import('~/pages/auth/register').then(m => m.default || m)
const PasswordEmail = () => import('~/pages/auth/password/email').then(m => m.default || m)
const PasswordReset = () => import('~/pages/auth/password/reset').then(m => m.default || m)
const NotFound = () => import('~/pages/errors/404').then(m => m.default || m)

const Home = () => import('~/pages/home').then(m => m.default || m)
const Settings = () => import('~/pages/settings/index').then(m => m.default || m)
const SettingsProfile = () => import('~/pages/settings/profile').then(m => m.default || m)
const SettingsPassword = () => import('~/pages/settings/password').then(m => m.default || m)

const CampSearch = () => import('~/pages/camp/search').then(m => m.default || m)
const CampSearchResults = () => import('~/pages/camp/results').then(m => m.default || m)
const CampDetails = () => import('~/pages/camp/details').then(m => m.default || m)
const CampParent1 = () => import('~/pages/camp/parent1').then(m => m.default || m)
const CampParent2 = () => import('~/pages/camp/parent2').then(m => m.default || m)
const CampChild = () => import('~/pages/camp/child').then(m => m.default || m)
const CampChildren = () => import('~/pages/camp/children').then(m => m.default || m)

export default [
  { path: '/camp', name: 'camp.search', component: CampSearch },
  { path: '/camp/results', name: 'camp.results', component: CampSearchResults },
  { path: '/camp/details', name: 'camp.details', component: CampDetails },
  { path: '/camp/register', name: 'camp.register', redirect: { name: 'camp.register.parent1' } },
  { path: '/camp/register/parent1', name: 'camp.register.parent1', component: CampParent1 },
  { path: '/camp/register/parent2', name: 'camp.register.parent2', component: CampParent2 },
  { path: '/camp/child-details', name: 'camp.child-details', component: CampChild },
  { path: '/camp/all-children', name: 'camp.all-children', component: CampChildren },

  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Register },
  { path: '/password/reset', name: 'password.request', component: PasswordEmail },
  { path: '/password/reset/:token', name: 'password.reset', component: PasswordReset },

  { path: '/home', name: 'home', component: Home },
  { path: '/settings',
    component: Settings,
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: SettingsProfile },
      { path: 'password', name: 'settings.password', component: SettingsPassword }
    ]
  },

  { path: '*', component: NotFound }
]
