import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Phone, Mail, MessageCircle, Clock, MapPin, Send } from 'lucide-react';
import { toast } from '@/hooks/use-toast';

export default function SupportContact() {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    category: '',
    message: ''
  });
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    // Simulate API call
    setTimeout(() => {
      toast({
        title: "Message Sent Successfully",
        description: "We'll get back to you within 24 hours.",
      });
      setFormData({ name: '', email: '', subject: '', category: '', message: '' });
      setIsLoading(false);
    }, 1500);
  };

  const contactInfo = [
    {
      icon: Phone,
      title: "Phone Support",
      content: "+1 (555) 123-4567",
      description: "Mon-Fri, 9 AM - 6 PM EST"
    },
    {
      icon: Mail,
      title: "Email Support",
      content: "support@courierpro.com",
      description: "Response within 24 hours"
    },
    {
      icon: MessageCircle,
      title: "Live Chat",
      content: "Available 24/7",
      description: "Instant support"
    },
    {
      icon: MapPin,
      title: "Office Address",
      content: "123 Business Plaza, Suite 100",
      description: "New York, NY 10001"
    }
  ];

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="text-center space-y-2">
        <h1 className="text-3xl font-bold text-foreground">Contact Support</h1>
        <p className="text-muted-foreground">
          Need help? We're here to assist you with any questions or issues.
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Contact Form */}
        <div className="lg:col-span-2">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Send className="h-5 w-5" />
                Send us a Message
              </CardTitle>
            </CardHeader>
            <CardContent>
              <form onSubmit={handleSubmit} className="space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label htmlFor="name">Full Name *</Label>
                    <Input
                      id="name"
                      value={formData.name}
                      onChange={(e) => setFormData({...formData, name: e.target.value})}
                      placeholder="Enter your full name"
                      required
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="email">Email Address *</Label>
                    <Input
                      id="email"
                      type="email"
                      value={formData.email}
                      onChange={(e) => setFormData({...formData, email: e.target.value})}
                      placeholder="Enter your email"
                      required
                    />
                  </div>
                </div>
                
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label htmlFor="category">Category *</Label>
                    <Select value={formData.category} onValueChange={(value) => setFormData({...formData, category: value})}>
                      <SelectTrigger>
                        <SelectValue placeholder="Select category" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="tracking">Package Tracking</SelectItem>
                        <SelectItem value="delivery">Delivery Issues</SelectItem>
                        <SelectItem value="account">Account Support</SelectItem>
                        <SelectItem value="billing">Billing Questions</SelectItem>
                        <SelectItem value="technical">Technical Support</SelectItem>
                        <SelectItem value="other">Other</SelectItem>
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="subject">Subject *</Label>
                    <Input
                      id="subject"
                      value={formData.subject}
                      onChange={(e) => setFormData({...formData, subject: e.target.value})}
                      placeholder="Brief description of your issue"
                      required
                    />
                  </div>
                </div>
                
                <div className="space-y-2">
                  <Label htmlFor="message">Message *</Label>
                  <Textarea
                    id="message"
                    value={formData.message}
                    onChange={(e) => setFormData({...formData, message: e.target.value})}
                    placeholder="Please describe your issue or question in detail..."
                    rows={6}
                    required
                  />
                </div>
                
                <Button
                  type="submit"
                  disabled={isLoading}
                  className="w-full"
                >
                  {isLoading ? 'Sending Message...' : 'Send Message'}
                </Button>
              </form>
            </CardContent>
          </Card>
        </div>

        {/* Contact Information */}
        <div className="space-y-6">
          {/* Contact Methods */}
          <Card>
            <CardHeader>
              <CardTitle>Get in Touch</CardTitle>
            </CardHeader>
            <CardContent className="space-y-6">
              {contactInfo.map((info, index) => (
                <div key={index} className="flex items-start gap-3">
                  <div className="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <info.icon className="h-5 w-5 text-primary" />
                  </div>
                  <div>
                    <h3 className="font-medium">{info.title}</h3>
                    <p className="text-sm font-medium text-primary">{info.content}</p>
                    <p className="text-xs text-muted-foreground">{info.description}</p>
                  </div>
                </div>
              ))}
            </CardContent>
          </Card>

          {/* FAQ Quick Links */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Clock className="h-5 w-5" />
                Quick Help
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
              <Button variant="outline" className="w-full justify-start" size="sm">
                How to track my package?
              </Button>
              <Button variant="outline" className="w-full justify-start" size="sm">
                Delivery time estimates
              </Button>
              <Button variant="outline" className="w-full justify-start" size="sm">
                Change delivery address
              </Button>
              <Button variant="outline" className="w-full justify-start" size="sm">
                Report missing package
              </Button>
              <Button variant="outline" className="w-full justify-start" size="sm">
                Account settings help
              </Button>
            </CardContent>
          </Card>

          {/* Business Hours */}
          <Card>
            <CardHeader>
              <CardTitle>Business Hours</CardTitle>
            </CardHeader>
            <CardContent className="space-y-2">
              <div className="flex justify-between text-sm">
                <span>Monday - Friday</span>
                <span>9:00 AM - 6:00 PM</span>
              </div>
              <div className="flex justify-between text-sm">
                <span>Saturday</span>
                <span>10:00 AM - 4:00 PM</span>
              </div>
              <div className="flex justify-between text-sm">
                <span>Sunday</span>
                <span>Closed</span>
              </div>
              <div className="text-xs text-muted-foreground mt-2">
                All times are in Eastern Standard Time (EST)
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
}