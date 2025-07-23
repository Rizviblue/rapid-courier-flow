import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Mail, ArrowLeft, CheckCircle } from 'lucide-react';
import { toast } from '@/hooks/use-toast';

interface ForgotPasswordProps {
  isOpen: boolean;
  onClose: () => void;
}

export default function ForgotPassword({ isOpen, onClose }: ForgotPasswordProps) {
  const [email, setEmail] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [isEmailSent, setIsEmailSent] = useState(false);

  const handleResetPassword = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    // Simulate API call
    setTimeout(() => {
      setIsEmailSent(true);
      setIsLoading(false);
      toast({
        title: "Reset Email Sent",
        description: "Please check your email for password reset instructions.",
      });
    }, 1500);
  };

  const handleClose = () => {
    setEmail('');
    setIsEmailSent(false);
    onClose();
  };

  return (
    <Dialog open={isOpen} onOpenChange={handleClose}>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            {!isEmailSent && (
              <Button
                variant="ghost"
                size="sm"
                onClick={handleClose}
                className="h-8 w-8 p-0"
              >
                <ArrowLeft className="h-4 w-4" />
              </Button>
            )}
            Forgot Password
          </DialogTitle>
        </DialogHeader>
        
        {!isEmailSent ? (
          <form onSubmit={handleResetPassword} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="reset-email">Email Address</Label>
              <div className="relative">
                <Mail className="absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                <Input
                  id="reset-email"
                  type="email"
                  placeholder="Enter your email address"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className="pl-10"
                  required
                />
              </div>
              <p className="text-sm text-muted-foreground">
                We'll send you a link to reset your password.
              </p>
            </div>
            
            <div className="flex gap-2">
              <Button
                type="button"
                variant="outline"
                onClick={handleClose}
                className="flex-1"
              >
                Cancel
              </Button>
              <Button
                type="submit"
                disabled={isLoading || !email}
                className="flex-1"
              >
                {isLoading ? 'Sending...' : 'Send Reset Link'}
              </Button>
            </div>
          </form>
        ) : (
          <div className="text-center space-y-4">
            <div className="flex justify-center">
              <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <CheckCircle className="h-8 w-8 text-green-600" />
              </div>
            </div>
            <div>
              <h3 className="text-lg font-semibold">Email Sent Successfully!</h3>
              <p className="text-muted-foreground mt-2">
                We've sent a password reset link to <strong>{email}</strong>
              </p>
              <p className="text-sm text-muted-foreground mt-2">
                Please check your email and follow the instructions to reset your password.
              </p>
            </div>
            <Button onClick={handleClose} className="w-full">
              Back to Login
            </Button>
          </div>
        )}
      </DialogContent>
    </Dialog>
  );
}