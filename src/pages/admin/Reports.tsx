import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { useCourierStore } from '@/store/courierStore';
import { BarChart3, Download, TrendingUp, TrendingDown, Package, Users, DollarSign, Clock } from 'lucide-react';
import { toast } from '@/hooks/use-toast';

export default function Reports() {
  const { couriers, getCourierStats } = useCourierStore();
  const [reportType, setReportType] = useState('daily');
  const [dateRange, setDateRange] = useState('last-7-days');

  const stats = getCourierStats();

  // Mock data for charts and reports
  const reportData = {
    dailyStats: [
      { date: '2024-01-15', couriers: 45, delivered: 38, revenue: 4500 },
      { date: '2024-01-16', couriers: 52, delivered: 41, revenue: 5200 },
      { date: '2024-01-17', couriers: 48, delivered: 39, revenue: 4800 },
      { date: '2024-01-18', couriers: 61, delivered: 48, revenue: 6100 },
      { date: '2024-01-19', couriers: 55, delivered: 44, revenue: 5500 },
      { date: '2024-01-20', couriers: 58, delivered: 46, revenue: 5800 },
      { date: '2024-01-21', couriers: 63, delivered: 51, revenue: 6300 }
    ],
    topAgents: [
      { name: 'Sarah Johnson', couriers: 156, delivered: 142, rating: 4.9 },
      { name: 'David Kim', couriers: 201, delivered: 185, rating: 4.8 },
      { name: 'Michael Chen', couriers: 134, delivered: 121, rating: 4.7 },
      { name: 'Emily Rodriguez', couriers: 89, delivered: 78, rating: 4.6 }
    ],
    topRoutes: [
      { route: 'New York → Los Angeles', count: 89, avgTime: '3.2 days' },
      { route: 'Chicago → Miami', count: 67, avgTime: '2.8 days' },
      { route: 'Houston → Seattle', count: 54, avgTime: '4.1 days' },
      { route: 'Phoenix → Boston', count: 43, avgTime: '3.7 days' }
    ]
  };

  const handleDownloadReport = (type: string) => {
    // Create a simple CSV content for demo
    const csvContent = `Report Type,${type}\nGenerated Date,${new Date().toLocaleDateString()}\nTotal Couriers,${stats.total}\nIn Transit,${stats.inTransit}\nDelivered,${stats.delivered}\nCancelled,${stats.cancelled}`;
    
    // Create and download file
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${type.toLowerCase().replace(' ', '_')}_report_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    
    toast({
      title: "Report Generated",
      description: `${type} report has been generated and downloaded.`,
    });
  };

  const handleExportData = (format: string) => {
    // Create sample data export
    const data = couriers.map(courier => ({
      tracking_number: courier.trackingNumber,
      sender: courier.senderName,
      receiver: courier.receiverName,
      from: courier.pickupCity,
      to: courier.deliveryCity,
      status: courier.status,
      created_date: courier.createdAt
    }));
    
    let content = '';
    let filename = '';
    
    if (format === 'Excel' || format === 'CSV') {
      // Create CSV content
      const headers = Object.keys(data[0]).join(',');
      const rows = data.map(row => Object.values(row).join(',')).join('\n');
      content = headers + '\n' + rows;
      filename = `courier_data_${new Date().toISOString().split('T')[0]}.csv`;
    } else {
      // JSON format
      content = JSON.stringify(data, null, 2);
      filename = `courier_data_${new Date().toISOString().split('T')[0]}.json`;
    }
    
    const blob = new Blob([content], { type: format === 'JSON' ? 'application/json' : 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
    
    toast({
      title: "Data Exported",
      description: `Data exported in ${format} format.`,
    });
  };

  // Calculate trends
  const trends = {
    couriers: { value: 15.3, direction: 'up' },
    delivery: { value: 8.7, direction: 'up' },
    revenue: { value: 12.4, direction: 'up' },
    cancellation: { value: 2.1, direction: 'down' }
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">Reports & Analytics</h1>
          <p className="text-muted-foreground">Comprehensive insights into your courier operations</p>
        </div>
        <div className="flex gap-2">
          <Button variant="outline" onClick={() => handleDownloadReport('PDF')}>
            <Download className="h-4 w-4 mr-2" />
            Download PDF
          </Button>
          <Button variant="outline" onClick={() => handleExportData('Excel')}>
            <Download className="h-4 w-4 mr-2" />
            Export Excel
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
                  <SelectItem value="custom">Custom Range</SelectItem>
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
                  <SelectItem value="last-year">Last Year</SelectItem>
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
            <CardTitle className="text-sm font-medium text-muted-foreground">Total Couriers</CardTitle>
            <Package className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats.total}</div>
            <div className="flex items-center text-xs text-green-600">
              <TrendingUp className="h-3 w-3 mr-1" />
              +{trends.couriers.value}% from last month
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Delivery Rate</CardTitle>
            <Clock className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">94.2%</div>
            <div className="flex items-center text-xs text-green-600">
              <TrendingUp className="h-3 w-3 mr-1" />
              +{trends.delivery.value}% from last month
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Revenue</CardTitle>
            <DollarSign className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">$45,231</div>
            <div className="flex items-center text-xs text-green-600">
              <TrendingUp className="h-3 w-3 mr-1" />
              +{trends.revenue.value}% from last month
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Active Agents</CardTitle>
            <Users className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">24</div>
            <div className="flex items-center text-xs text-red-600">
              <TrendingDown className="h-3 w-3 mr-1" />
              -{trends.cancellation.value}% cancellation rate
            </div>
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Daily Performance Chart */}
        <Card>
          <CardHeader>
            <CardTitle>Daily Performance (Last 7 Days)</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {reportData.dailyStats.map((day, index) => (
                <div key={index} className="flex items-center justify-between p-4 border rounded-lg">
                  <div>
                    <div className="font-medium">{new Date(day.date).toLocaleDateString()}</div>
                    <div className="text-sm text-muted-foreground">
                      {day.delivered}/{day.couriers} delivered
                    </div>
                  </div>
                  <div className="text-right">
                    <div className="font-medium">${day.revenue.toLocaleString()}</div>
                    <div className="text-sm text-muted-foreground">
                      {((day.delivered / day.couriers) * 100).toFixed(1)}% success
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Top Performing Agents */}
        <Card>
          <CardHeader>
            <CardTitle>Top Performing Agents</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {reportData.topAgents.map((agent, index) => (
                <div key={index} className="flex items-center justify-between p-4 border rounded-lg">
                  <div className="flex items-center gap-3">
                    <div className="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-primary-foreground font-medium">
                      {index + 1}
                    </div>
                    <div>
                      <div className="font-medium">{agent.name}</div>
                      <div className="text-sm text-muted-foreground">
                        {agent.delivered}/{agent.couriers} completed
                      </div>
                    </div>
                  </div>
                  <div className="text-right">
                    <Badge variant="outline">★ {agent.rating}</Badge>
                    <div className="text-sm text-muted-foreground mt-1">
                      {((agent.delivered / agent.couriers) * 100).toFixed(1)}%
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Top Routes and Quick Actions */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <Card className="lg:col-span-2">
          <CardHeader>
            <CardTitle>Most Popular Routes</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {reportData.topRoutes.map((route, index) => (
                <div key={index} className="flex items-center justify-between p-4 border rounded-lg">
                  <div>
                    <div className="font-medium">{route.route}</div>
                    <div className="text-sm text-muted-foreground">
                      Average delivery time: {route.avgTime}
                    </div>
                  </div>
                  <div className="text-right">
                    <Badge variant="secondary">{route.count} orders</Badge>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Quick Actions</CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            <Button 
              className="w-full justify-start" 
              variant="outline"
              onClick={() => handleDownloadReport('Courier Performance')}
            >
              <Download className="h-4 w-4 mr-2" />
              Download Courier Report
            </Button>
            <Button 
              className="w-full justify-start" 
              variant="outline"
              onClick={() => handleDownloadReport('Financial')}
            >
              <Download className="h-4 w-4 mr-2" />
              Download Financial Report
            </Button>
            <Button 
              className="w-full justify-start" 
              variant="outline"
              onClick={() => handleDownloadReport('Agent Performance')}
            >
              <Download className="h-4 w-4 mr-2" />
              Download Agent Report
            </Button>
            <Button 
              className="w-full justify-start" 
              variant="outline"
              onClick={() => handleExportData('CSV')}
            >
              <Download className="h-4 w-4 mr-2" />
              Export Raw Data
            </Button>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}