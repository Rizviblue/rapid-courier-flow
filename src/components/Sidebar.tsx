import { NavLink, useNavigate } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { useAuthStore, UserRole } from '@/store/authStore';
import {
  LayoutDashboard,
  Package,
  List,
  Users,
  UserCheck,
  BarChart3,
  Settings,
  LogOut,
  Plus,
  Search
} from 'lucide-react';

interface SidebarProps {
  role: UserRole;
}

const sidebarItems = {
  admin: [
    { icon: LayoutDashboard, label: 'Dashboard', path: '/admin/dashboard' },
    { icon: Plus, label: 'Add Courier', path: '/admin/add-courier' },
    { icon: List, label: 'Courier List', path: '/admin/couriers' },
    { icon: Users, label: 'Agent Management', path: '/admin/agents' },
    { icon: UserCheck, label: 'Customer Management', path: '/admin/customers' },
    { icon: BarChart3, label: 'Reports', path: '/admin/reports' },
    { icon: Settings, label: 'Settings', path: '/admin/settings' }
  ],
  agent: [
    { icon: LayoutDashboard, label: 'Dashboard', path: '/agent/dashboard' },
    { icon: Plus, label: 'Add Courier', path: '/agent/add-courier' },
    { icon: List, label: 'Courier List', path: '/agent/couriers' },
    { icon: BarChart3, label: 'Reports', path: '/agent/reports' },
    { icon: Settings, label: 'Settings', path: '/agent/settings' }
  ],
  user: [
    { icon: LayoutDashboard, label: 'Dashboard', path: '/user/dashboard' },
    { icon: Search, label: 'Track Package', path: '/user/track' },
    { icon: List, label: 'My Packages', path: '/user/packages' },
    { icon: UserCheck, label: 'Support', path: '/user/support' },
    { icon: Settings, label: 'Settings', path: '/user/settings' }
  ]
};

export default function Sidebar({ role }: SidebarProps) {
  const { logout, user } = useAuthStore();
  const navigate = useNavigate();
  const items = sidebarItems[role];

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <div className="w-64 bg-primary text-primary-foreground h-screen flex flex-col">
      {/* Logo/Brand */}
      <div className="p-6 border-b border-primary-dark">
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 bg-primary-foreground rounded-lg flex items-center justify-center">
            <Package className="h-6 w-6 text-primary" />
          </div>
          <div>
            <h1 className="text-xl font-bold">CourierPro</h1>
            <p className="text-xs opacity-80">{role.charAt(0).toUpperCase() + role.slice(1)} Panel</p>
          </div>
        </div>
      </div>

      {/* Navigation */}
      <nav className="flex-1 px-4 py-6 space-y-2">
        {items.map((item) => (
          <NavLink
            key={item.path}
            to={item.path}
            className={({ isActive }) =>
              cn(
                "flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-sm font-medium",
                isActive
                  ? "bg-primary-foreground text-primary"
                  : "text-primary-foreground hover:bg-primary-dark"
              )
            }
          >
            <item.icon className="h-5 w-5" />
            {item.label}
          </NavLink>
        ))}
      </nav>

      {/* User Info & Logout */}
      <div className="p-4 border-t border-primary-dark">
        <div className="mb-4 px-4 py-2">
          <p className="text-sm font-medium">{user?.name}</p>
          <p className="text-xs opacity-80">{user?.email}</p>
        </div>
        <Button
          onClick={handleLogout}
          variant="outline"
          size="sm"
          className="w-full bg-transparent border-primary-foreground text-primary-foreground hover:bg-primary-foreground hover:text-primary"
        >
          <LogOut className="h-4 w-4 mr-2" />
          Logout
        </Button>
      </div>
    </div>
  );
}