import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { Badge } from '@/components/ui/badge';
import { useAuthStore } from '@/store/authStore';
import { 
  Settings, 
  User, 
  Bell, 
  Shield, 
  Globe, 
  Database, 
  Mail, 
  Phone, 
  Save,
  Key,
  Palette,
  Monitor
} from 'lucide-react';
import { toast } from '@/hooks/use-toast';

export default function AdminSettings() {
  const { user } = useAuthStore();
  
  const [settings, setSettings] = useState({
    // Profile Settings
    name: user?.name || '',
    email: user?.email || '',
    phone: '+1 234 567 8900',
    timezone: 'America/New_York',
    language: 'english',
    
    // Notification Settings
    emailNotifications: true,
    smsNotifications: false,
    pushNotifications: true,
    orderUpdates: true,
    systemAlerts: true,
    marketingEmails: false,
    
    // Security Settings
    twoFactorAuth: false,
    sessionTimeout: '30',
    passwordExpiry: '90',
    loginAttempts: '5',
    
    // System Settings
    currency: 'USD',
    dateFormat: 'MM/DD/YYYY',
    timeFormat: '12',
    theme: 'light',
    autoBackup: true,
    backupFrequency: 'daily'
  });

  const handleSaveSettings = (section: string) => {
    toast({
      title: "Settings Saved",
      description: `${section} settings have been updated successfully.`,
    });
  };

  const handleResetSettings = () => {
    if (window.confirm('Are you sure you want to reset all settings to default?')) {
      toast({
        title: "Settings Reset",
        description: "All settings have been reset to default values.",
      });
    }
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">Settings</h1>
          <p className="text-muted-foreground">Manage your system preferences and account settings</p>
        </div>
        <Button variant="outline" onClick={handleResetSettings}>
          Reset to Default
        </Button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
              <Label htmlFor="name">Full Name</Label>
              <Input
                id="name"
                value={settings.name}
                onChange={(e) => setSettings({...settings, name: e.target.value})}
              />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="email">Email</Label>
              <Input
                id="email"
                type="email"
                value={settings.email}
                onChange={(e) => setSettings({...settings, email: e.target.value})}
              />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="phone">Phone</Label>
              <Input
                id="phone"
                value={settings.phone}
                onChange={(e) => setSettings({...settings, phone: e.target.value})}
              />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="timezone">Timezone</Label>
              <Select value={settings.timezone} onValueChange={(value) => setSettings({...settings, timezone: value})}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="America/New_York">Eastern Time (UTC-5)</SelectItem>
                  <SelectItem value="America/Chicago">Central Time (UTC-6)</SelectItem>
                  <SelectItem value="America/Denver">Mountain Time (UTC-7)</SelectItem>
                  <SelectItem value="America/Los_Angeles">Pacific Time (UTC-8)</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="language">Language</Label>
              <Select value={settings.language} onValueChange={(value) => setSettings({...settings, language: value})}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="english">English</SelectItem>
                  <SelectItem value="spanish">Spanish</SelectItem>
                  <SelectItem value="french">French</SelectItem>
                  <SelectItem value="german">German</SelectItem>
                </SelectContent>
              </Select>
            </div>
            
            <Button className="w-full" onClick={() => handleSaveSettings('Profile')}>
              <Save className="h-4 w-4 mr-2" />
              Save Profile
            </Button>
          </CardContent>
        </Card>

        {/* Notification Settings */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Bell className="h-5 w-5" />
              Notifications
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2">
                <Mail className="h-4 w-4" />
                <Label>Email Notifications</Label>
              </div>
              <Switch
                checked={settings.emailNotifications}
                onCheckedChange={(checked) => setSettings({...settings, emailNotifications: checked})}
              />
            </div>
            
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2">
                <Phone className="h-4 w-4" />
                <Label>SMS Notifications</Label>
              </div>
              <Switch
                checked={settings.smsNotifications}
                onCheckedChange={(checked) => setSettings({...settings, smsNotifications: checked})}
              />
            </div>
            
            <div className="flex items-center justify-between">
              <Label>Push Notifications</Label>
              <Switch
                checked={settings.pushNotifications}
                onCheckedChange={(checked) => setSettings({...settings, pushNotifications: checked})}
              />
            </div>
            
            <Separator />
            
            <div className="flex items-center justify-between">
              <Label>Order Updates</Label>
              <Switch
                checked={settings.orderUpdates}
                onCheckedChange={(checked) => setSettings({...settings, orderUpdates: checked})}
              />
            </div>
            
            <div className="flex items-center justify-between">
              <Label>System Alerts</Label>
              <Switch
                checked={settings.systemAlerts}
                onCheckedChange={(checked) => setSettings({...settings, systemAlerts: checked})}
              />
            </div>
            
            <div className="flex items-center justify-between">
              <Label>Marketing Emails</Label>
              <Switch
                checked={settings.marketingEmails}
                onCheckedChange={(checked) => setSettings({...settings, marketingEmails: checked})}
              />
            </div>
            
            <Button className="w-full" onClick={() => handleSaveSettings('Notification')}>
              <Save className="h-4 w-4 mr-2" />
              Save Notifications
            </Button>
          </CardContent>
        </Card>

        {/* Security & System Settings */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Shield className="h-5 w-5" />
              Security & System
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            {/* Security Section */}
            <div className="space-y-4">
              <div className="flex items-center gap-2">
                <Key className="h-4 w-4" />
                <Label className="font-medium">Security</Label>
              </div>
              
              <div className="flex items-center justify-between">
                <Label>Two-Factor Authentication</Label>
                <Switch
                  checked={settings.twoFactorAuth}
                  onCheckedChange={(checked) => setSettings({...settings, twoFactorAuth: checked})}
                />
              </div>
              
              <div className="space-y-2">
                <Label>Session Timeout (minutes)</Label>
                <Select value={settings.sessionTimeout} onValueChange={(value) => setSettings({...settings, sessionTimeout: value})}>
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="15">15 minutes</SelectItem>
                    <SelectItem value="30">30 minutes</SelectItem>
                    <SelectItem value="60">1 hour</SelectItem>
                    <SelectItem value="120">2 hours</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
            
            <Separator />
            
            {/* System Section */}
            <div className="space-y-4">
              <div className="flex items-center gap-2">
                <Monitor className="h-4 w-4" />
                <Label className="font-medium">System</Label>
              </div>
              
              <div className="space-y-2">
                <Label>Currency</Label>
                <Select value={settings.currency} onValueChange={(value) => setSettings({...settings, currency: value})}>
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="USD">US Dollar (USD)</SelectItem>
                    <SelectItem value="EUR">Euro (EUR)</SelectItem>
                    <SelectItem value="GBP">British Pound (GBP)</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              
              <div className="space-y-2">
                <Label>Theme</Label>
                <Select value={settings.theme} onValueChange={(value) => setSettings({...settings, theme: value})}>
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="light">Light</SelectItem>
                    <SelectItem value="dark">Dark</SelectItem>
                    <SelectItem value="auto">Auto</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              
              <div className="flex items-center justify-between">
                <Label>Auto Backup</Label>
                <Switch
                  checked={settings.autoBackup}
                  onCheckedChange={(checked) => setSettings({...settings, autoBackup: checked})}
                />
              </div>
            </div>
            
            <Button className="w-full" onClick={() => handleSaveSettings('Security & System')}>
              <Save className="h-4 w-4 mr-2" />
              Save Settings
            </Button>
          </CardContent>
        </Card>
      </div>

      {/* System Status */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Database className="h-5 w-5" />
            System Status
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold text-green-600">Online</div>
              <div className="text-sm text-muted-foreground">System Status</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold">99.9%</div>
              <div className="text-sm text-muted-foreground">Uptime</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold">1.2GB</div>
              <div className="text-sm text-muted-foreground">Database Size</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold">v2.1.0</div>
              <div className="text-sm text-muted-foreground">Version</div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}