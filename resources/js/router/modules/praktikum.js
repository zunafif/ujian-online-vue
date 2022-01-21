import Layout from '@/layout';

const praktikumRoutes = {
  path: '/lab',
  component: Layout,
  redirect: '/lab/praktikum',
  name: 'LabInfor',
  meta: {
    title: 'Lab. Informatika',
    icon: 'education',
  },
  children: [
    {
      path: 'praktikum',
      component: () => import('@/views/praktikum/List'),
      name: 'PraktikumList',
      meta: { title: 'Praktikum', icon: 'education' },
    },
    {
      path: 'modul',
      component: () => import('@/views/praktikum/Modul'),
      name: 'ModulList',
      meta: { title: 'Modul', icon: 'layout' },
    },
  ],
};
export default praktikumRoutes;
