import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { useCourierStore } from '@/store/courierStore';
import { BarChart3, Download, TrendingUp, Package, Clock } from 'lucide-react';
import { toast } from '@/hooks/use-toast';

export default function AgentReports() {
  const { couriers, getCourierStats } = useCourierStore();
  const [reportType, setReportType] = useState('daily');
  const [dateRange, setDateRange] = useState('last-7-days');

  const stats = getCourierStats();
  
  // Agent-specific stats (simulated)
  const agentStats = {
    total: Math.floor(stats.total * 0.6),
    inTransit: Math.floor(stats.inTransit * 0.7),
    delivered: Math.floor(stats.delivered * 0.8),
    cancelled: Math.max(0, stats.cancelled - 1)
  };

  const handleDownloadReport = (type: string) => {
    const csvContent = `Agent Report,${type}\nGenerated Date,${new Date().toLocaleDateString()}\nMy Couriers,${agentStats.total}\nIn Transit,${agentStats.inTransit}\nDelivered,${agentStats.delivered}\nCancelled,${agentStats.cancelled}`;
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `agent_${type.toLowerCase().replace(' ', '_')}_report_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    
    toast({
      title: "Report Downloaded",
      description: `${type} report has been generated and downloaded.`,
    });
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">My Reports</h1>
          <p className="text-muted-foreground">View your courier performance and statistics</p>
        </div>
        <div className="flex gap-2">
          <Button variant="outline" onClick={() => handleDownloadReport('Performance')}>
            <Download className="h-4 w-4 mr-2" />
            Download Report
          </Button>
        </div>
      </div>

      {/* Filters */}
      <Card>
        <CardHeader>
          <CardTitle>Report Filters</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label className="text-sm font-medium mb-2 block">Report Type</label>
              <Select value={reportType} onValueChange={setReportType}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="daily">Daily Reports</SelectItem>
                  <SelectItem value="weekly">Weekly Reports</SelectItem>
                  <SelectItem value="monthly">Monthly Reports</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div>
              <label className="text-sm font-medium mb-2 block">Date Range</label>
              <Select value={dateRange} onValueChange={setDateRange}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="last-7-days">Last 7 Days</SelectItem>
                  <SelectItem value="last-30-days">Last 30 Days</SelectItem>
                  <SelectItem value="last-3-months">Last 3 Months</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="flex items-end">
              <Button className="w-full">
                <BarChart3 className="h-4 w-4 mr-2" />
                Generate Report
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Key Metrics */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">My Couriers</CardTitle>
            <Package className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{agentStats.total}</div>
            <div className="flex items-center text-xs text-green-600">
              <TrendingUp className="h-3 w-3 mr-1" />
              +12% from last month
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Success Rate</CardTitle>
            <Clock className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">95.2%</div>
            <div className="flex items-center text-xs text-green-600">
              <TrendingUp className="h-3 w-3 mr-1" />
              +2.1% from last month
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Delivered</CardTitle>
            <Package className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{agentStats.delivered}</div>
            <div className="flex items-center text-xs text-green-600">
              <TrendingUp className="h-3 w-3 mr-1" />
              +18% this week
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">In Transit</CardTitle>
            <Package className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{agentStats.inTransit}</div>
            <div className="flex items-center text-xs text-blue-600">
              <TrendingUp className="h-3 w-3 mr-1" />
              +5% from yesterday
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Recent Activity */}
      <Card>
        <CardHeader>
          <CardTitle>Recent Activity</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            {couriers.slice(0, 5).map((courier) => (
              <div key={courier.id} className="flex items-center justify-between p-4 border rounded-lg">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <Package className="h-5 w-5 text-primary-foreground" />
                  </div>
                  <div>
                    <div className="font-medium">{courier.trackingNumber}</div>
                    <div className="text-sm text-muted-foreground">
                      {courier.pickupCity} → {courier.deliveryCity}
                    </div>
                  </div>
                </div>
                <div className="text-right">
                  <Badge variant="outline">{courier.status}</Badge>
                  <div className="text-sm text-muted-foreground mt-1">
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
        <CardContent className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <Button 
            variant="outline" 
            className="justify-start"
            onClick={() => handleDownloadReport('Daily Performance')}
          >
            <Download className="h-4 w-4 mr-2" />
            Download Daily Report
          </Button>
          <Button 
            variant="outline" 
            className="justify-start"
            onClick={() => handleDownloadReport('Weekly Summary')}
          >
            <Download className="h-4 w-4 mr-2" />
            Download Weekly Report
          </Button>
          <Button 
            variant="outline" 
            className="justify-start"
            onClick={() => handleDownloadReport('Monthly Analysis')}
          >
            <Download className="h-4 w-4 mr-2" />
            Download Monthly Report
          </Button>
        </CardContent>
      </Card>
    </div>
  );
}