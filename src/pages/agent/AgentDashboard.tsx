import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { useCourierStore } from '@/store/courierStore';
import StatusBadge from '@/components/StatusBadge';
import { Package, Truck, CheckCircle, XCircle, Plus, BarChart3 } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function AgentDashboard() {
  const { couriers, getCourierStats } = useCourierStore();
  const stats = getCourierStats();
  
  // Get recent couriers for this agent (simulated)
  const agentCouriers = couriers.slice(0, 4);

  return (
    <div className="p-6 space-y-6">
      {/* Welcome Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">Agent Dashboard</h1>
          <p className="text-muted-foreground">Manage your assigned courier operations</p>
        </div>
        <Button className="bg-primary hover:bg-primary-dark" asChild>
          <Link to="/agent/add-courier">
            <Plus className="h-4 w-4 mr-2" />
            Add Courier
          </Link>
        </Button>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <Card className="border-l-4 border-l-blue-500">
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">
              My Couriers
            </CardTitle>
            <Package className="h-5 w-5 text-blue-500" />
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold">{Math.floor(stats.total * 0.6)}</div>
            <p className="text-xs text-green-600 mt-1">+12% from last week</p>
          </CardContent>
        </Card>

        <Card className="border-l-4 border-l-status-transit">
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">
              In Transit
            </CardTitle>
            <Truck className="h-5 w-5 text-status-transit" />
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold">{Math.max(1, Math.floor(stats.inTransit * 0.7))}</div>
            <p className="text-xs text-blue-600 mt-1">+5% from yesterday</p>
          </CardContent>
        </Card>

        <Card className="border-l-4 border-l-status-delivered">
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">
              Delivered
            </CardTitle>
            <CheckCircle className="h-5 w-5 text-status-delivered" />
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold">{Math.floor(stats.delivered * 0.8)}</div>
            <p className="text-xs text-green-600 mt-1">+18% this week</p>
          </CardContent>
        </Card>

        <Card className="border-l-4 border-l-status-cancelled">
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">
              Cancelled
            </CardTitle>
            <XCircle className="h-5 w-5 text-status-cancelled" />
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold">{Math.max(0, stats.cancelled - 1)}</div>
            <p className="text-xs text-red-600 mt-1">1% from last week</p>
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* My Recent Couriers */}
        <Card className="lg:col-span-2">
          <CardHeader className="flex flex-row items-center justify-between">
            <CardTitle>My Recent Couriers</CardTitle>
            <Link to="/agent/couriers">
              <Button variant="outline" size="sm">View All</Button>
            </Link>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {agentCouriers.map((courier) => (
                <div key={courier.id} className="flex items-center justify-between p-4 border rounded-lg hover:bg-muted/50 transition-colors">
                  <div className="flex-1">
                    <div className="flex items-center gap-3">
                      <div className="font-medium">{courier.trackingNumber}</div>
                      <StatusBadge status={courier.status} />
                    </div>
                    <div className="text-sm text-muted-foreground mt-1">
                      {courier.senderName} → {courier.receiverName}
                    </div>
                    <div className="text-xs text-muted-foreground">
                      {courier.pickupCity} to {courier.deliveryCity}
                    </div>
                  </div>
                  <div className="text-right">
                    <div className="text-sm font-medium">{courier.deliveryCity}</div>
                    <div className="text-xs text-muted-foreground">
                      {new Date(courier.createdAt).toLocaleDateString()}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Quick Actions */}
        <Card>
          <CardHeader>
            <CardTitle>Quick Actions</CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            <Button className="w-full justify-start" variant="outline" asChild>
              <Link to="/agent/add-courier">
                <Plus className="h-4 w-4 mr-2" />
                Add New Courier
              </Link>
            </Button>
            <Button className="w-full justify-start" variant="outline" asChild>
              <Link to="/agent/couriers">
                <Package className="h-4 w-4 mr-2" />
                View All Couriers
              </Link>
            </Button>
            <Button className="w-full justify-start" variant="outline" asChild>
              <Link to="/agent/reports">
                <BarChart3 className="h-4 w-4 mr-2" />
                Download Reports
              </Link>
            </Button>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}