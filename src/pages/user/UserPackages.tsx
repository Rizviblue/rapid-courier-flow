import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useCourierStore } from '@/store/courierStore';
import StatusBadge from '@/components/StatusBadge';
import { Search, Package, Calendar, MapPin, Eye, Printer } from 'lucide-react';
import { toast } from '@/hooks/use-toast';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';

export default function UserPackages() {
  const { couriers } = useCourierStore();
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedPackage, setSelectedPackage] = useState<any>(null);
  const [viewDialogOpen, setViewDialogOpen] = useState(false);
  
  // Filter packages for current user (simulated)
  const userPackages = couriers.filter(courier => 
    searchTerm === '' || 
    courier.trackingNumber.toLowerCase().includes(searchTerm.toLowerCase()) ||
    courier.pickupCity.toLowerCase().includes(searchTerm.toLowerCase()) ||
    courier.deliveryCity.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleViewDetails = (trackingNumber: string) => {
    const pkg = userPackages.find(p => p.trackingNumber === trackingNumber);
    setSelectedPackage(pkg);
    setViewDialogOpen(true);
  };

  const handlePrintLabel = (trackingNumber: string) => {
    // Create a simple print content
    const printContent = `
      <div style="padding: 20px; font-family: Arial, sans-serif;">
        <h2>Shipping Label</h2>
        <p><strong>Tracking Number:</strong> ${trackingNumber}</p>
        <p><strong>Date:</strong> ${new Date().toLocaleDateString()}</p>
        <div style="border: 2px solid #000; padding: 10px; margin: 20px 0;">
          <h3>Courier Pro Shipping Label</h3>
          <p>Tracking: ${trackingNumber}</p>
        </div>
      </div>
    `;
    
    const printWindow = window.open('', '_blank');
    if (printWindow) {
      printWindow.document.write(printContent);
      printWindow.document.close();
      printWindow.print();
    }
    
    toast({
      title: "Printing Label",
      description: `Shipping label for ${trackingNumber} is being prepared`,
    });
  };

  const packageStats = {
    total: userPackages.length,
    pending: userPackages.filter(p => p.status === 'pending').length,
    inTransit: userPackages.filter(p => p.status === 'in_transit').length,
    delivered: userPackages.filter(p => p.status === 'delivered').length,
    cancelled: userPackages.filter(p => p.status === 'cancelled').length
  };

  return (
    <>
    <div className="p-6 space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-foreground">My Packages</h1>
        <p className="text-muted-foreground">Track and manage all your shipments</p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Total Packages</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{packageStats.total}</div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Pending</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-orange-600">{packageStats.pending}</div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">In Transit</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-blue-600">{packageStats.inTransit}</div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Delivered</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-green-600">{packageStats.delivered}</div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Cancelled</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-red-600">{packageStats.cancelled}</div>
          </CardContent>
        </Card>
      </div>

      {/* Search */}
      <Card>
        <CardHeader>
          <CardTitle>Search Packages</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4" />
            <Input
              placeholder="Search by tracking number, city, or route..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
        </CardContent>
      </Card>

      {/* Packages Table */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Package className="h-5 w-5" />
            My Packages ({userPackages.length})
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="overflow-x-auto">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Tracking Number</TableHead>
                  <TableHead>Route</TableHead>
                  <TableHead>Type</TableHead>
                  <TableHead>Weight</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Date Shipped</TableHead>
                  <TableHead>Expected Delivery</TableHead>
                  <TableHead>Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {userPackages.map((pkg) => (
                  <TableRow key={pkg.id}>
                    <TableCell className="font-medium">
                      {pkg.trackingNumber}
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <MapPin className="h-4 w-4 text-muted-foreground" />
                        <div className="text-sm">
                          <div>{pkg.pickupCity}</div>
                          <div className="text-muted-foreground">→ {pkg.deliveryCity}</div>
                        </div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="secondary">{pkg.courierType}</Badge>
                    </TableCell>
                    <TableCell>{pkg.weight} kg</TableCell>
                    <TableCell>
                      <StatusBadge status={pkg.status} />
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-1">
                        <Calendar className="h-4 w-4 text-muted-foreground" />
                        {new Date(pkg.createdAt).toLocaleDateString()}
                      </div>
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-1">
                        <Calendar className="h-4 w-4 text-muted-foreground" />
                        {pkg.deliveryDate ? new Date(pkg.deliveryDate).toLocaleDateString() : 'TBD'}
                      </div>
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => handleViewDetails(pkg.trackingNumber)}
                        >
                          <Eye className="h-4 w-4" />
                        </Button>
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => handlePrintLabel(pkg.trackingNumber)}
                        >
                          <Printer className="h-4 w-4" />
                        </Button>
                      </div>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>

          {userPackages.length === 0 && (
            <div className="text-center py-8 text-muted-foreground">
              {searchTerm 
                ? 'No packages found matching your search criteria.' 
                : 'No packages found. Start shipping to see your packages here.'}
            </div>
          )}
        </CardContent>
      </Card>

      {/* Recent Activity */}
      <Card>
        <CardHeader>
          <CardTitle>Recent Package Activity</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            {userPackages.slice(0, 5).map((pkg) => (
              <div key={pkg.id} className="flex items-center justify-between p-4 border rounded-lg">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <Package className="h-5 w-5 text-primary-foreground" />
                  </div>
                  <div>
                    <div className="font-medium">{pkg.trackingNumber}</div>
                    <div className="text-sm text-muted-foreground">
                      {pkg.pickupCity} → {pkg.deliveryCity}
                    </div>
                  </div>
                </div>
                <div className="text-right">
                  <StatusBadge status={pkg.status} />
                  <div className="text-sm text-muted-foreground mt-1">
                    {new Date(pkg.createdAt).toLocaleDateString()}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>

    {/* View Package Details Dialog */}
    <Dialog open={viewDialogOpen} onOpenChange={setViewDialogOpen}>
      <DialogContent className="max-w-2xl">
        <DialogHeader>
          <DialogTitle>Package Details</DialogTitle>
        </DialogHeader>
        {selectedPackage && (
          <div className="grid grid-cols-2 gap-4">
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Tracking Number</Label>
              <p className="font-medium">{selectedPackage.trackingNumber}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Status</Label>
              <div className="mt-1">
                <StatusBadge status={selectedPackage.status} />
              </div>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">From</Label>
              <p className="font-medium">{selectedPackage.pickupCity}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">To</Label>
              <p className="font-medium">{selectedPackage.deliveryCity}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Type</Label>
              <p className="font-medium">{selectedPackage.courierType}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Weight</Label>
              <p className="font-medium">{selectedPackage.weight} kg</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Shipped Date</Label>
              <p className="font-medium">{new Date(selectedPackage.createdAt).toLocaleDateString()}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Expected Delivery</Label>
              <p className="font-medium">{selectedPackage.deliveryDate ? new Date(selectedPackage.deliveryDate).toLocaleDateString() : 'TBD'}</p>
            </div>
          </div>
        )}
        <div className="flex justify-end gap-2 mt-4">
          <Button 
            variant="outline"
            onClick={() => selectedPackage && handlePrintLabel(selectedPackage.trackingNumber)}
          >
            <Printer className="h-4 w-4 mr-2" />
            Print Label
          </Button>
          <Button onClick={() => setViewDialogOpen(false)}>
            Close
          </Button>
        </div>
      </DialogContent>
    </Dialog>
    </>
  );
}