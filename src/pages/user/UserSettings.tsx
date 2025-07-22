import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useAuthStore } from '@/store/authStore';
import { User, Bell, Save, CreditCard, Home, Phone, Mail } from 'lucide-react';
import { toast } from '@/hooks/use-toast';

export default function UserSettings() {
  const { user } = useAuthStore();
  
  const [settings, setSettings] = useState({
    // Profile Settings
    name: user?.name || '',
    email: user?.email || '',
    phone: user?.phone || '+1 234 567 8900',
    
    // Address Settings
    defaultAddress: '123 Main St, New York, NY 10001',
    billingAddress: '123 Main St, New York, NY 10001',
    
    // Notification Preferences
    emailNotifications: true,
    smsNotifications: true,
    packageUpdates: true,
    promotionalEmails: false,
    deliveryAlerts: true,
    
    // Preferences
    preferredDeliveryTime: 'anytime',
    packageInstructions: 'Leave at front door',
    currency: 'USD',
    language: 'english'
  });

  const handleSaveSettings = (section: string) => {
    toast({
      title: "Settings Saved",
      description: `${section} settings have been updated successfully.`,
    });
  };

  const handleChangePassword = () => {
    toast({
      title: "Password Change",
      description: "Password change request has been sent to your email.",
    });
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-foreground">Account Settings</h1>
        <p className="text-muted-foreground">Manage your profile and delivery preferences</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Profile Information */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <User className="h-5 w-5" />
              Profile Information
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="userName">Full Name</Label>
              <Input
                id="userName"
                value={settings.name}
                onChange={(e) => setSettings({...settings, name: e.target.value})}
              />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="userEmail">Email Address</Label>
              <div className="flex items-center gap-2">
                <Mail className="h-4 w-4 text-muted-foreground" />
                <Input
                  id="userEmail"
                  type="email"
                  value={settings.email}
                  onChange={(e) => setSettings({...settings, email: e.target.value})}
                />
              </div>
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="userPhone">Phone Number</Label>
              <div className="flex items-center gap-2">
                <Phone className="h-4 w-4 text-muted-foreground" />
                <Input
                  id="userPhone"
                  value={settings.phone}
                  onChange={(e) => setSettings({...settings, phone: e.target.value})}
                />
              </div>
            </div>
            
            <div className="space-y-2">
              <Label>Language</Label>
              <Select value={settings.language} onValueChange={(value) => setSettings({...settings, language: value})}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="english">English</SelectItem>
                  <SelectItem value="spanish">Spanish</SelectItem>
                  <SelectItem value="french">French</SelectItem>
                </SelectContent>
              </Select>
            </div>
            
            <div className="flex gap-2">
              <Button className="flex-1" onClick={() => handleSaveSettings('Profile')}>
                <Save className="h-4 w-4 mr-2" />
                Save Profile
              </Button>
              <Button variant="outline" onClick={handleChangePassword}>
                Change Password
              </Button>
            </div>
          </CardContent>
        </Card>

        {/* Address Settings */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Home className="h-5 w-5" />
              Address Settings
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="defaultAddress">Default Shipping Address</Label>
              <Input
                id="defaultAddress"
                value={settings.defaultAddress}
                onChange={(e) => setSettings({...settings, defaultAddress: e.target.value})}
                placeholder="Enter your default shipping address"
              />
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="billingAddress">Billing Address</Label>
              <Input
                id="billingAddress"
                value={settings.billingAddress}
                onChange={(e) => setSettings({...settings, billingAddress: e.target.value})}
                placeholder="Enter your billing address"
              />
            </div>
            
            <div className="space-y-2">
              <Label>Preferred Delivery Time</Label>
              <Select value={settings.preferredDeliveryTime} onValueChange={(value) => setSettings({...settings, preferredDeliveryTime: value})}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="anytime">Anytime</SelectItem>
                  <SelectItem value="morning">Morning (9 AM - 12 PM)</SelectItem>
                  <SelectItem value="afternoon">Afternoon (12 PM - 5 PM)</SelectItem>
                  <SelectItem value="evening">Evening (5 PM - 8 PM)</SelectItem>
                </SelectContent>
              </Select>
            </div>
            
            <div className="space-y-2">
              <Label htmlFor="packageInstructions">Special Delivery Instructions</Label>
              <Input
                id="packageInstructions"
                value={settings.packageInstructions}
                onChange={(e) => setSettings({...settings, packageInstructions: e.target.value})}
                placeholder="e.g., Leave at front door, Ring bell"
              />
            </div>
            
            <Button className="w-full" onClick={() => handleSaveSettings('Address')}>
              <Save className="h-4 w-4 mr-2" />
              Save Address Settings
            </Button>
          </CardContent>
        </Card>
      </div>

      {/* Notification Preferences */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Bell className="h-5 w-5" />
            Notification Preferences
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
              
              <div className="flex items-center justify-between">
                <Label>Package Updates</Label>
                <Switch
                  checked={settings.packageUpdates}
                  onCheckedChange={(checked) => setSettings({...settings, packageUpdates: checked})}
                />
              </div>
            </div>
            
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <Label>Delivery Alerts</Label>
                <Switch
                  checked={settings.deliveryAlerts}
                  onCheckedChange={(checked) => setSettings({...settings, deliveryAlerts: checked})}
                />
              </div>
              
              <div className="flex items-center justify-between">
                <Label>Promotional Emails</Label>
                <Switch
                  checked={settings.promotionalEmails}
                  onCheckedChange={(checked) => setSettings({...settings, promotionalEmails: checked})}
                />
              </div>
            </div>
          </div>
          
          <div className="mt-6">
            <Button onClick={() => handleSaveSettings('Notifications')}>
              <Save className="h-4 w-4 mr-2" />
              Save Notification Preferences
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* Account Statistics */}
      <Card>
        <CardHeader>
          <CardTitle>Account Overview</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold text-blue-600">23</div>
              <div className="text-sm text-muted-foreground">Total Packages</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold text-green-600">21</div>
              <div className="text-sm text-muted-foreground">Delivered</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold text-orange-600">2</div>
              <div className="text-sm text-muted-foreground">In Transit</div>
            </div>
            <div className="text-center p-4 border rounded-lg">
              <div className="text-2xl font-bold">$1,250</div>
              <div className="text-sm text-muted-foreground">Total Spent</div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}