import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { useCourierStore } from '@/store/courierStore';
import StatusBadge from '@/components/StatusBadge';
import { Package, Truck, CheckCircle, XCircle, Plus, Users, BarChart3, Clock } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function AdminDashboard() {
  const { couriers, getCourierStats } = useCourierStore();
  const stats = getCourierStats();
  
  // Get recent couriers (last 5)
  const recentCouriers = couriers.slice(0, 5);

  // Today's activity stats
  const todayStats = {
    newCouriers: 23,
    deliveries: 45,
    activeAgents: 12
  };

  return (
    <div className="p-6 space-y-6">
      {/* Welcome Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">Admin Dashboard</h1>
          <p className="text-muted-foreground">Monitor and manage your courier operations</p>
        </div>
        <div className="flex gap-3">
          <Button className="bg-primary hover:bg-primary-dark" asChild>
            <Link to="/admin/analytics">
              <BarChart3 className="h-4 w-4 mr-2" />
              Analytics
            </Link>
          </Button>
          <Button className="bg-primary hover:bg-primary-dark" asChild>
            <Link to="/admin/add-courier">
              <Plus className="h-4 w-4 mr-2" />
              Add Courier
            </Link>
          </Button>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <Card className="border-l-4 border-l-blue-500">
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">
              Total Couriers
            </CardTitle>
            <Package className="h-5 w-5 text-blue-500" />
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold">{stats.total.toLocaleString()}</div>
            <p className="text-xs text-green-600 mt-1">+19% from last month</p>
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
            <div className="text-3xl font-bold">{stats.inTransit}</div>
            <p className="text-xs text-blue-600 mt-1">+8% from yesterday</p>
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
            <div className="text-3xl font-bold">{stats.delivered}</div>
            <p className="text-xs text-green-600 mt-1">+15% this week</p>
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
            <div className="text-3xl font-bold">{stats.cancelled}</div>
            <p className="text-xs text-red-600 mt-1">3% from last week</p>
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Recent Couriers */}
        <Card className="lg:col-span-2">
          <CardHeader className="flex flex-row items-center justify-between">
            <CardTitle>Recent Couriers</CardTitle>
            <Link to="/admin/couriers">
              <Button variant="outline" size="sm">View All</Button>
            </Link>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentCouriers.map((courier) => (
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

        {/* Quick Actions & Today's Activity */}
        <div className="space-y-6">
          {/* Quick Actions */}
          <Card>
            <CardHeader>
              <CardTitle>Quick Actions</CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
              <Button className="w-full justify-start" variant="outline" asChild>
                <Link to="/admin/add-courier">
                  <Plus className="h-4 w-4 mr-2" />
                  Add New Courier
                </Link>
              </Button>
              <Button className="w-full justify-start" variant="outline" asChild>
                <Link to="/admin/agents">
                  <Users className="h-4 w-4 mr-2" />
                  Manage Agents
                </Link>
              </Button>
              <Button className="w-full justify-start" variant="outline" asChild>
                <Link to="/admin/reports">
                  <BarChart3 className="h-4 w-4 mr-2" />
                  View Reports
                </Link>
              </Button>
            </CardContent>
          </Card>

          {/* Today's Activity */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Clock className="h-5 w-5" />
                Today's Activity
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="flex items-center justify-between">
                <span className="text-sm text-muted-foreground">New Couriers</span>
                <Badge variant="secondary">{todayStats.newCouriers}</Badge>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-muted-foreground">Deliveries</span>
                <Badge variant="secondary" className="bg-green-100 text-green-800">
                  {todayStats.deliveries}
                </Badge>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm text-muted-foreground">Active Agents</span>
                <Badge variant="secondary" className="bg-blue-100 text-blue-800">
                  {todayStats.activeAgents}
                </Badge>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
}