import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Eye, EyeOff, Mail, Lock, AlertCircle, HelpCircle } from 'lucide-react';
import { useAuthStore, UserRole } from '@/store/authStore';
import { toast } from '@/hooks/use-toast';
import ForgotPassword from './ForgotPassword';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [isForgotPasswordOpen, setIsForgotPasswordOpen] = useState(false);
  
  const { login, demoLogin } = useAuthStore();
  const navigate = useNavigate();

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    // Simulate login delay
    setTimeout(() => {
      // Demo login logic
      if (email === 'admin@courierpro.com' && password === 'password') {
        demoLogin('admin');
        navigate('/admin/dashboard');
      } else if (email === 'agent@courierpro.com' && password === 'password') {
        demoLogin('agent');
        navigate('/agent/dashboard');
      } else if (email === 'user@courierpro.com' && password === 'password') {
        demoLogin('user');
        navigate('/user/dashboard');
      } else {
        toast({
          title: "Login Failed",
          description: "Invalid email or password. Use demo credentials below.",
          variant: "destructive"
        });
      }
      setIsLoading(false);
    }, 1000);
  };

  const handleDemoLogin = (role: UserRole) => {
    demoLogin(role);
    navigate(`/${role}/dashboard`);
    toast({
      title: "Demo Login Successful",
      description: `Logged in as ${role.charAt(0).toUpperCase() + role.slice(1)}`,
    });
  };

  return (
    <div className="min-h-screen bg-background flex items-center justify-center p-4">
      <div className="w-full max-w-md space-y-6">
        {/* Header */}
        <div className="text-center mb-8">
          <h1 className="text-2xl font-bold text-muted-foreground mb-2">
            Courier Management System
          </h1>
        </div>

        {/* Login Form */}
        <Card className="shadow-lg">
          <CardHeader className="text-center pb-4">
            <CardTitle className="text-2xl font-semibold">Sign In</CardTitle>
            <CardDescription>
              Enter your credentials to access your account
            </CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <form onSubmit={handleLogin} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="email">Email</Label>
                <div className="relative">
                  <Mail className="absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                  <Input
                    id="email"
                    type="email"
                    placeholder="Enter your email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="pl-10"
                    required
                  />
                </div>
              </div>
              
              <div className="space-y-2">
                <Label htmlFor="password">Password</Label>
                <div className="relative">
                  <Lock className="absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                  <Input
                    id="password"
                    type={showPassword ? 'text' : 'password'}
                    placeholder="Enter your password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="pl-10 pr-10"
                    required
                  />
                  <Button
                    type="button"
                    variant="ghost"
                    size="sm"
                    className="absolute right-1 top-1 h-8 w-8 p-0"
                    onClick={() => setShowPassword(!showPassword)}
                  >
                    {showPassword ? (
                      <EyeOff className="h-4 w-4" />
                    ) : (
                      <Eye className="h-4 w-4" />
                    )}
                  </Button>
                </div>
              </div>

              <Button 
                type="submit" 
                className="w-full bg-primary hover:bg-primary-dark" 
                disabled={isLoading}
              >
                {isLoading ? 'Signing In...' : 'Sign In'}
              </Button>
            </form>

            <div className="flex items-center justify-between text-sm">
              <button
                type="button"
                onClick={() => setIsForgotPasswordOpen(true)}
                className="text-primary hover:underline"
              >
                Forgot Password?
              </button>
              <div className="text-muted-foreground">
                Don't have an account?{' '}
                <Link to="/register" className="text-primary hover:underline">
                  Register here
                </Link>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Demo Credentials */}
        <Card className="shadow-lg">
          <CardHeader className="pb-4">
            <CardTitle className="text-lg">Demo Credentials</CardTitle>
            <CardDescription>
              Click any button below to auto-fill login credentials
            </CardDescription>
          </CardHeader>
          <CardContent className="space-y-3">
            <div className="grid grid-cols-1 gap-3">
              <Button
                onClick={() => handleDemoLogin('admin')}
                variant="outline"
                className="justify-between"
              >
                <span className="font-medium">Admin</span>
                <span className="text-xs text-muted-foreground">Full system access</span>
              </Button>
              
              <Button
                onClick={() => handleDemoLogin('agent')}
                variant="outline"
                className="justify-between"
              >
                <span className="font-medium">Agent</span>
                <span className="text-xs text-muted-foreground">Courier management</span>
              </Button>
              
              <Button
                onClick={() => handleDemoLogin('user')}
                variant="outline"
                className="justify-between"
              >
                <span className="font-medium">User</span>
                <span className="text-xs text-muted-foreground">Track packages</span>
              </Button>
            </div>
            
            <div className="flex items-center gap-2 text-sm text-muted-foreground bg-orange-50 p-3 rounded-lg">
              <AlertCircle className="h-4 w-4 text-orange-500" />
              <span>Password for all demo accounts is either <strong>password</strong> or <strong>123456</strong></span>
            </div>
          </CardContent>
        </Card>

        {/* Support Contact Link */}
        <Card className="text-center">
          <CardContent className="pt-6">
            <div className="flex items-center justify-center gap-2 text-muted-foreground">
              <HelpCircle className="h-4 w-4" />
              <span className="text-sm">Need help?</span>
              <Link to="/support" className="text-primary hover:underline text-sm">
                Contact Support
              </Link>
            </div>
          </CardContent>
        </Card>
      </div>

      <ForgotPassword 
        isOpen={isForgotPasswordOpen} 
        onClose={() => setIsForgotPasswordOpen(false)} 
      />
    </div>
  );
}