import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useAuthStore } from '@/store/authStore';
import { User, Bell, Save, Key, Monitor, MapPin } from 'lucide-react';
import { toast } from '@/hooks/use-toast';

export default function AgentSettings() {
  const { user } = useAuthStore();
  
  const [settings, setSettings] = useState({
    // Profile Settings
    name: user?.name || '',
    email: user?.email || '',
    phone: '+1 234 567 8901',
    city: user?.city || 'New York',
    timezone: 'America/New_York',
    
    // Notification Settings
    emailNotifications: true,
    smsNotifications: true,
    orderUpdates: true,
    deliveryAlerts: true,
    
    // Work Preferences
    workingHours: '9to5',
    availability: true,
    autoAssign: false,
    maxDailyOrders: '20'
  });

  const handleSaveSettings = (section: string) => {
    toast({
      title: "Settings Saved",
      description: `${section} settings have been updated successfully.`,
    });
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-foreground">Agent Settings</h1>
        <p className="text-muted-foreground">Manage your profile and work preferences</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Profile Settings */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <User className="h-5 w-5" />
              Profile Settings
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="agentName">Full Name</Label>
              <Input
                id="agentName"
                value={settings.name}
                onChange={(e) => setSettings({...settings, name: e.target.value})}
              />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="agentEmail">Email</Label>
              <Input
                id="agentEmail"
                type="email"
                value={settings.email}
                onChange={(e) => setSettings({...settings, email: e.target.value})}
              />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="agentPhone">Phone</Label>
              <Input
                id="agentPhone"
                value={settings.phone}
                onChange={(e) => setSettings({...settings, phone: e.target.value})}
              />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="agentCity">Working City</Label>
              <div className="flex items-center gap-2">
                <MapPin className="h-4 w-4 text-muted-foreground" />
                <Select value={settings.city} onValueChange={(value) => setSettings({...settings, city: value})}>
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="New York">New York</SelectItem>
                    <SelectItem value="Los Angeles">Los Angeles</SelectItem>
                    <SelectItem value="Chicago">Chicago</SelectItem>
                    <SelectItem value="Houston">Houston</SelectItem>
                    <SelectItem value="Phoenix">Phoenix</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
            
            <Button className="w-full" onClick={() => handleSaveSettings('Profile')}>
              <Save className="h-4 w-4 mr-2" />
              Save Profile
            </Button>
          </CardContent>
        </Card>

        {/* Work Preferences */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Monitor className="h-5 w-5" />
              Work Preferences
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label>Working Hours</Label>
              <Select value={settings.workingHours} onValueChange={(value) => setSettings({...settings, workingHours: value})}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="9to5">9 AM - 5 PM</SelectItem>
                  <SelectItem value="8to6">8 AM - 6 PM</SelectItem>
                  <SelectItem value="7to7">7 AM - 7 PM</SelectItem>
                  <SelectItem value="24hours">24/7 Available</SelectItem>
                </SelectContent>
              </Select>
            </div>
            
            <div className="flex items-center justify-between">
              <Label>Currently Available</Label>
              <Switch
                checked={settings.availability}
                onCheckedChange={(checked) => setSettings({...settings, availability: checked})}
              />
            </div>
            
            <div className="flex items-center justify-between">
              <Label>Auto-assign Orders</Label>
              <Switch
                checked={settings.autoAssign}
                onCheckedChange={(checked) => setSettings({...settings, autoAssign: checked})}
              />
            </div>
            
            <div className="space-y-2">
              <Label>Max Daily Orders</Label>
              <Select value={settings.maxDailyOrders} onValueChange={(value) => setSettings({...settings, maxDailyOrders: value})}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="10">10 orders</SelectItem>
                  <SelectItem value="15">15 orders</SelectItem>
                  <SelectItem value="20">20 orders</SelectItem>
                  <SelectItem value="25">25 orders</SelectItem>
                  <SelectItem value="unlimited">Unlimited</SelectItem>
                </SelectContent>
              </Select>
            </div>
            
            <Button className="w-full" onClick={() => handleSaveSettings('Work Preferences')}>
              <Save className="h-4 w-4 mr-2" />
              Save Preferences
            </Button>
          </CardContent>
        </Card>
      </div>

      {/* Notification Settings */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Bell className="h-5 w-5" />
            Notification Settings
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <Label>Email Notifications</Label>
                <Switch
                  checked={settings.emailNotifications}
                  onCheckedChange={(checked) => setSettings({...settings, emailNotifications: checked})}
                />
              </div>
              
              <div className="flex items-center justify-between">
                <Label>SMS Notifications</Label>
                <Switch
                  checked={settings.smsNotifications}
                  onCheckedChange={(checked) => setSettings({...settings, smsNotifications: checked})}
                />
              </div>
            </div>
            
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <Label>Order Updates</Label>
                <Switch
                  checked={settings.orderUpdates}
                  onCheckedChange={(checked) => setSettings({...settings, orderUpdates: checked})}
                />
              </div>
              
              <div className="flex items-center justify-between">
                <Label>Delivery Alerts</Label>
                <Switch
                  checked={settings.deliveryAlerts}
                  onCheckedChange={(checked) => setSettings({...settings, deliveryAlerts: checked})}
                />
              </div>
            </div>
          </div>
          
          <div className="mt-6">
            <Button onClick={() => handleSaveSettings('Notification')}>
              <Save className="h-4 w-4 mr-2" />
              Save Notification Settings
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* Performance Stats */}
      <Card>
        <CardHeader>
          <CardTitle>Performance Overview</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold text-green-600">4.8</div>
              <div className="text-sm text-muted-foreground">Rating</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold">156</div>
              <div className="text-sm text-muted-foreground">Total Deliveries</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold">95%</div>
              <div className="text-sm text-muted-foreground">Success Rate</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold">2.3h</div>
              <div className="text-sm text-muted-foreground">Avg. Delivery Time</div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}