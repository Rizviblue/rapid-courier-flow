import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { User, Mail, Phone, MapPin, Calendar, Package } from 'lucide-react';

interface Agent {
  id: string;
  name: string;
  email: string;
  phone: string;
  city: string;
  status: 'active' | 'inactive';
  totalCouriers: number;
  joinedDate: string;
}

interface ViewAgentDialogProps {
  agent: Agent | null;
  isOpen: boolean;
  onClose: () => void;
}

export default function ViewAgentDialog({ agent, isOpen, onClose }: ViewAgentDialogProps) {
  if (!agent) return null;

  const recentActivity = [
    { action: 'Delivered package', tracking: 'CP123456', time: '2 hours ago' },
    { action: 'Picked up package', tracking: 'CP123457', time: '4 hours ago' },
    { action: 'Updated delivery status', tracking: 'CP123458', time: '6 hours ago' },
    { action: 'Delivered package', tracking: 'CP123459', time: '1 day ago' }
  ];

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="max-w-2xl">
        <DialogHeader>
          <DialogTitle>Agent Details</DialogTitle>
        </DialogHeader>
        
        <div className="space-y-6">
          {/* Agent Info Card */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-3">
                <div className="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                  <User className="h-6 w-6 text-primary-foreground" />
                </div>
                <div>
                  <h3 className="text-xl font-semibold">{agent.name}</h3>
                  <div className="flex items-center gap-2">
                    <Badge 
                      variant={agent.status === 'active' ? 'default' : 'secondary'}
                      className={agent.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}
                    >
                      {agent.status}
                    </Badge>
                    <span className="text-sm text-muted-foreground">ID: {agent.id}</span>
                  </div>
                </div>
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-3">
                  <div className="flex items-center gap-2">
                    <Mail className="h-4 w-4 text-muted-foreground" />
                    <span className="text-sm">{agent.email}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Phone className="h-4 w-4 text-muted-foreground" />
                    <span className="text-sm">{agent.phone}</span>
                  </div>
                </div>
                <div className="space-y-3">
                  <div className="flex items-center gap-2">
                    <MapPin className="h-4 w-4 text-muted-foreground" />
                    <span className="text-sm">{agent.city}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Calendar className="h-4 w-4 text-muted-foreground" />
                    <span className="text-sm">Joined {new Date(agent.joinedDate).toLocaleDateString()}</span>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          {/* Performance Stats */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium text-muted-foreground">Total Couriers</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="flex items-center gap-2">
                  <Package className="h-5 w-5 text-primary" />
                  <span className="text-2xl font-bold">{agent.totalCouriers}</span>
                </div>
              </CardContent>
            </Card>
            
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium text-muted-foreground">Success Rate</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-green-600">94.5%</div>
              </CardContent>
            </Card>
            
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium text-muted-foreground">Rating</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">4.8 ⭐</div>
              </CardContent>
            </Card>
          </div>

          {/* Recent Activity */}
          <Card>
            <CardHeader>
              <CardTitle>Recent Activity</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-3">
                {recentActivity.map((activity, index) => (
                  <div key={index} className="flex items-center justify-between p-3 border rounded-lg">
                    <div>
                      <div className="font-medium text-sm">{activity.action}</div>
                      <div className="text-xs text-muted-foreground">Tracking: {activity.tracking}</div>
                    </div>
                    <div className="text-xs text-muted-foreground">{activity.time}</div>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>

          <div className="flex justify-end">
            <Button onClick={onClose}>Close</Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
}