import { create } from 'zustand';
import { persist } from 'zustand/middleware';

export type UserRole = 'admin' | 'agent' | 'user';

export interface User {
  id: string;
  name: string;
  email: string;
  role: UserRole;
  phone?: string;
  city?: string;
}

interface AuthState {
  user: User | null;
  isAuthenticated: boolean;
  login: (user: User) => void;
  logout: () => void;
  demoLogin: (role: UserRole) => void;
}

const demoUsers: Record<UserRole, User> = {
  admin: {
    id: '1',
    name: 'John Smith',
    email: 'admin@courierpro.com',
    role: 'admin'
  },
  agent: {
    id: '2',
    name: 'Sarah Johnson',
    email: 'agent@courierpro.com',
    role: 'agent',
    city: 'New York'
  },
  user: {
    id: '3',
    name: 'Mike Wilson',
    email: 'user@courierpro.com',
    role: 'user',
    phone: '+1 234 567 8900'
  }
};

export const useAuthStore = create<AuthState>()(
  persist(
    (set) => ({
      user: null,
      isAuthenticated: false,
      login: (user) => set({ user, isAuthenticated: true }),
      logout: () => set({ user: null, isAuthenticated: false }),
      demoLogin: (role) => {
        const user = demoUsers[role];
        set({ user, isAuthenticated: true });
      }
    }),
    {
      name: 'courier-auth'
    }
  )
);