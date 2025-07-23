import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { useCourierStore } from '@/store/courierStore';
import { 
  BarChart3, 
  TrendingUp, 
  TrendingDown, 
  Package, 
  Truck, 
  CheckCircle, 
  XCircle,
  Calendar,
  Download,
  Filter,
  Clock
} from 'lucide-react';
import { useState } from 'react';
import { toast } from '@/hooks/use-toast';

export default function Analytics() {
  const { couriers, getCourierStats } = useCourierStore();
  const stats = getCourierStats();
  const [timeRange, setTimeRange] = useState('7days');
  const [selectedMetric, setSelectedMetric] = useState('all');

  // Mock analytics data
  const analyticsData = {
    deliveryPerformance: {
      onTime: 87,
      delayed: 10,
      failed: 3
    },
    monthlyTrends: [
      { month: 'Jan', deliveries: 456, revenue: 12400 },
      { month: 'Feb', deliveries: 523, revenue: 14200 },
      { month: 'Mar', deliveries: 601, revenue: 16800 },
      { month: 'Apr', deliveries: 578, revenue: 15900 },
      { month: 'May', deliveries: 689, revenue: 18700 },
      { month: 'Jun', deliveries: 734, revenue: 19900 }
    ],
    topRoutes: [
      { route: 'New York → Los Angeles', count: 234, revenue: 8900 },
      { route: 'Chicago → Houston', count: 189, revenue: 6700 },
      { route: 'Miami → Atlanta', count: 156, revenue: 5400 },
      { route: 'Seattle → San Francisco', count: 134, revenue: 4800 },
      { route: 'Boston → Philadelphia', count: 123, revenue: 4200 }
    ],
    agentPerformance: [
      { name: 'Sarah Johnson', deliveries: 89, rating: 4.8, efficiency: 94 },
      { name: 'Michael Chen', deliveries: 76, rating: 4.7, efficiency: 92 },
      { name: 'Emily Rodriguez', deliveries: 67, rating: 4.6, efficiency: 88 },
      { name: 'David Kim', deliveries: 82, rating: 4.9, efficiency: 96 }
    ]
  };

  const handleExportData = () => {
    toast({
      title: "Export Started",
      description: "Your analytics report is being generated and will be downloaded shortly.",
    });
  };

  const kpiCards = [
    {
      title: "Total Revenue",
      value: "$89,420",
      change: "+12.5%",
      trend: "up",
      icon: TrendingUp,
      color: "text-green-600"
    },
    {
      title: "Delivery Rate",
      value: "94.7%",
      change: "+2.1%",
      trend: "up",
      icon: CheckCircle,
      color: "text-green-600"
    },
    {
      title: "Avg Delivery Time",
      value: "2.3 days",
      change: "-0.4 days",
      trend: "down",
      icon: Clock,
      color: "text-blue-600"
    },
    {
      title: "Customer Satisfaction",
      value: "4.8/5",
      change: "+0.2",
      trend: "up",
      icon: TrendingUp,
      color: "text-green-600"
    }
  ];

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">Analytics Dashboard</h1>
          <p className="text-muted-foreground">Comprehensive insights and performance metrics</p>
        </div>
        <div className="flex gap-3">
          <Select value={timeRange} onValueChange={setTimeRange}>
            <SelectTrigger className="w-40">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="7days">Last 7 Days</SelectItem>
              <SelectItem value="30days">Last 30 Days</SelectItem>
              <SelectItem value="90days">Last 3 Months</SelectItem>
              <SelectItem value="year">This Year</SelectItem>
            </SelectContent>
          </Select>
          <Button onClick={handleExportData} variant="outline">
            <Download className="h-4 w-4 mr-2" />
            Export Report
          </Button>
        </div>
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {kpiCards.map((kpi, index) => (
          <Card key={index}>
            <CardHeader className="flex flex-row items-center justify-between pb-2">
              <CardTitle className="text-sm font-medium text-muted-foreground">
                {kpi.title}
              </CardTitle>
              <kpi.icon className={`h-5 w-5 ${kpi.color}`} />
            </CardHeader>
            <CardContent>
              <div className="text-3xl font-bold">{kpi.value}</div>
              <div className={`text-sm flex items-center gap-1 mt-1 ${
                kpi.trend === 'up' ? 'text-green-600' : 'text-blue-600'
              }`}>
                {kpi.trend === 'up' ? (
                  <TrendingUp className="h-3 w-3" />
                ) : (
                  <TrendingDown className="h-3 w-3" />
                )}
                {kpi.change} from last period
              </div>
            </CardContent>
          </Card>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Delivery Performance */}
        <Card>
          <CardHeader>
            <CardTitle>Delivery Performance</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <span className="text-sm font-medium">On-Time Deliveries</span>
                <div className="flex items-center gap-2">
                  <div className="w-24 bg-gray-200 rounded-full h-2">
                    <div 
                      className="bg-green-500 h-2 rounded-full" 
                      style={{width: `${analyticsData.deliveryPerformance.onTime}%`}}
                    ></div>
                  </div>
                  <span className="text-sm font-bold">{analyticsData.deliveryPerformance.onTime}%</span>
                </div>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm font-medium">Delayed Deliveries</span>
                <div className="flex items-center gap-2">
                  <div className="w-24 bg-gray-200 rounded-full h-2">
                    <div 
                      className="bg-yellow-500 h-2 rounded-full" 
                      style={{width: `${analyticsData.deliveryPerformance.delayed}%`}}
                    ></div>
                  </div>
                  <span className="text-sm font-bold">{analyticsData.deliveryPerformance.delayed}%</span>
                </div>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-sm font-medium">Failed Deliveries</span>
                <div className="flex items-center gap-2">
                  <div className="w-24 bg-gray-200 rounded-full h-2">
                    <div 
                      className="bg-red-500 h-2 rounded-full" 
                      style={{width: `${analyticsData.deliveryPerformance.failed}%`}}
                    ></div>
                  </div>
                  <span className="text-sm font-bold">{analyticsData.deliveryPerformance.failed}%</span>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Top Routes */}
        <Card>
          <CardHeader>
            <CardTitle>Top Delivery Routes</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {analyticsData.topRoutes.map((route, index) => (
                <div key={index} className="flex items-center justify-between p-3 border rounded-lg">
                  <div>
                    <div className="font-medium text-sm">{route.route}</div>
                    <div className="text-xs text-muted-foreground">{route.count} deliveries</div>
                  </div>
                  <div className="text-right">
                    <div className="font-bold text-sm">${route.revenue}</div>
                    <Badge variant="secondary" className="text-xs">#{index + 1}</Badge>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Agent Performance */}
      <Card>
        <CardHeader>
          <CardTitle>Agent Performance Overview</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b">
                  <th className="text-left py-2">Agent Name</th>
                  <th className="text-center py-2">Deliveries</th>
                  <th className="text-center py-2">Rating</th>
                  <th className="text-center py-2">Efficiency</th>
                  <th className="text-center py-2">Status</th>
                </tr>
              </thead>
              <tbody>
                {analyticsData.agentPerformance.map((agent, index) => (
                  <tr key={index} className="border-b">
                    <td className="py-3">
                      <div className="flex items-center gap-2">
                        <div className="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                          <span className="text-xs font-bold text-primary-foreground">
                            {agent.name.split(' ').map(n => n[0]).join('')}
                          </span>
                        </div>
                        {agent.name}
                      </div>
                    </td>
                    <td className="text-center py-3">{agent.deliveries}</td>
                    <td className="text-center py-3">
                      <Badge variant="secondary">{agent.rating} ⭐</Badge>
                    </td>
                    <td className="text-center py-3">
                      <Badge 
                        variant={agent.efficiency >= 95 ? 'default' : agent.efficiency >= 90 ? 'secondary' : 'outline'}
                        className={
                          agent.efficiency >= 95 ? 'bg-green-100 text-green-800' :
                          agent.efficiency >= 90 ? 'bg-yellow-100 text-yellow-800' :
                          'bg-red-100 text-red-800'
                        }
                      >
                        {agent.efficiency}%
                      </Badge>
                    </td>
                    <td className="text-center py-3">
                      <Badge variant="default" className="bg-green-100 text-green-800">
                        Active
                      </Badge>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      {/* Monthly Trends Chart Placeholder */}
      <Card>
        <CardHeader>
          <CardTitle>Monthly Delivery Trends</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="h-64 bg-muted/20 rounded-lg flex items-center justify-center">
            <div className="text-center">
              <BarChart3 className="h-12 w-12 text-muted-foreground mx-auto mb-2" />
              <p className="text-muted-foreground">Chart visualization would be rendered here</p>
              <p className="text-sm text-muted-foreground mt-1">
                Data: {analyticsData.monthlyTrends.length} months of delivery statistics
              </p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}