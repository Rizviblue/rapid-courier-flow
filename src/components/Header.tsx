import { Search, Bell, User, ChevronDown } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useAuthStore } from '@/store/authStore';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Badge } from '@/components/ui/badge';
import { toast } from '@/hooks/use-toast';

export default function Header() {
  const { user, logout } = useAuthStore();
  const [notificationsOpen, setNotificationsOpen] = useState(false);
  const [profileOpen, setProfileOpen] = useState(false);

  const notifications = [
    { id: 1, title: 'New courier assigned', message: 'CMS001234 has been assigned to you', time: '5 min ago', unread: true },
    { id: 2, title: 'Delivery completed', message: 'Package CMS001235 delivered successfully', time: '1 hour ago', unread: true },
    { id: 3, title: 'System update', message: 'New features available in dashboard', time: '2 hours ago', unread: false }
  ];

  const handleNotificationClick = () => {
    setNotificationsOpen(true);
  };

  const handleProfileSettings = () => {
    setProfileOpen(true);
    toast({
      title: "Profile Settings",
      description: "Opening profile settings...",
    });
  };

  const markAllAsRead = () => {
    toast({
      title: "Notifications",
      description: "All notifications marked as read",
    });
    setNotificationsOpen(false);
  };

  return (
    <>
    <header className="bg-card border-b border-border px-6 py-4">
      <div className="flex items-center justify-between">
        {/* Search */}
        <div className="flex-1 max-w-md">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4" />
            <Input
              placeholder="Search couriers, tracking numbers..."
              className="pl-10 w-full"
            />
          </div>
        </div>

        {/* Right side */}
        <div className="flex items-center gap-4">
          {/* Notifications */}
          <Button 
            variant="ghost" 
            size="sm" 
            className="relative"
            onClick={handleNotificationClick}
          >
            <Bell className="h-5 w-5" />
            <Badge className="absolute -top-1 -right-1 h-5 w-5 text-xs p-0 flex items-center justify-center bg-red-500">
              3
            </Badge>
          </Button>

          {/* Profile Dropdown */}
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" className="flex items-center gap-2">
                <div className="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                  <User className="h-4 w-4 text-primary-foreground" />
                </div>
                <div className="text-left">
                  <p className="text-sm font-medium">{user?.name}</p>
                  <p className="text-xs text-muted-foreground capitalize">{user?.role}</p>
                </div>
                <ChevronDown className="h-4 w-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56">
              <DropdownMenuItem>
                <User className="mr-2 h-4 w-4" />
                <span onClick={handleProfileSettings}>Profile Settings</span>
              </DropdownMenuItem>
              <DropdownMenuSeparator />
              <DropdownMenuItem onClick={logout}>
                Logout
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
    </header>

    {/* Notifications Dialog */}
    <Dialog open={notificationsOpen} onOpenChange={setNotificationsOpen}>
      <DialogContent className="max-w-md">
        <DialogHeader>
          <DialogTitle className="flex items-center justify-between">
            <span>Notifications</span>
            <Button variant="ghost" size="sm" onClick={markAllAsRead}>
              Mark all as read
            </Button>
          </DialogTitle>
        </DialogHeader>
        <div className="space-y-4 max-h-96 overflow-y-auto">
          {notifications.map((notification) => (
            <div 
              key={notification.id} 
              className={`p-4 border rounded-lg ${notification.unread ? 'bg-blue-50 border-blue-200' : 'bg-gray-50'}`}
            >
              <div className="flex items-start justify-between">
                <div className="flex-1">
                  <h4 className="font-medium text-sm">{notification.title}</h4>
                  <p className="text-sm text-muted-foreground mt-1">{notification.message}</p>
                  <p className="text-xs text-muted-foreground mt-2">{notification.time}</p>
                </div>
                {notification.unread && (
                  <div className="w-2 h-2 bg-blue-500 rounded-full mt-1"></div>
                )}
              </div>
            </div>
          ))}
        </div>
      </DialogContent>
    </Dialog>

    {/* Profile Settings Dialog */}
    <Dialog open={profileOpen} onOpenChange={setProfileOpen}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Profile Settings</DialogTitle>
        </DialogHeader>
        <div className="space-y-4">
          <div className="text-center">
            <div className="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
              <User className="h-10 w-10 text-primary-foreground" />
            </div>
            <h3 className="text-lg font-medium">{user?.name}</h3>
            <p className="text-sm text-muted-foreground">{user?.email}</p>
            <Badge className="mt-2 capitalize">{user?.role}</Badge>
          </div>
          <div className="grid grid-cols-2 gap-4">
            <Button variant="outline" onClick={() => setProfileOpen(false)}>
              Edit Profile
            </Button>
            <Button variant="outline" onClick={() => setProfileOpen(false)}>
              Change Password
            </Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
    </>
  );
}