import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useCourierStore } from '@/store/courierStore';
import StatusBadge from '@/components/StatusBadge';
import { Search, Package, MapPin, Calendar, Printer } from 'lucide-react';
import { toast } from '@/hooks/use-toast';

export default function UserDashboard() {
  const { couriers } = useCourierStore();
  const [trackingNumber, setTrackingNumber] = useState('');
  const [searchResult, setSearchResult] = useState<any>(null);

  const handleTrackPackage = () => {
    if (!trackingNumber.trim()) {
      toast({
        title: "Invalid Input",
        description: "Please enter a tracking number.",
        variant: "destructive"
      });
      return;
    }

    const found = couriers.find(c => 
      c.trackingNumber.toLowerCase() === trackingNumber.toLowerCase()
    );

    if (found) {
      setSearchResult(found);
      toast({
        title: "Package Found",
        description: `Found package with tracking number ${trackingNumber}`,
      });
    } else {
      setSearchResult(null);
      toast({
        title: "Package Not Found",
        description: "No package found with this tracking number.",
        variant: "destructive"
      });
    }
  };

  const handlePrint = () => {
    if (searchResult) {
      window.print();
      toast({
        title: "Print Dialog Opened",
        description: "Package details are ready to print.",
      });
    }
  };

  // Get user's packages (simulated - in real app, filter by user ID)
  const userPackages = couriers.slice(0, 3);

  return (
    <div className="p-6 space-y-6">
      {/* Welcome Header */}
      <div>
        <h1 className="text-3xl font-bold text-foreground">Track Your Packages</h1>
        <p className="text-muted-foreground">Enter your tracking number to get real-time updates</p>
      </div>

      {/* Package Tracking */}
      <Card className="max-w-2xl">
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Search className="h-5 w-5" />
            Track Package
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="tracking">Tracking Number</Label>
            <div className="flex gap-2">
              <Input
                id="tracking"
                placeholder="Enter tracking number (e.g., CMS001234)"
                value={trackingNumber}
                onChange={(e) => setTrackingNumber(e.target.value)}
                onKeyPress={(e) => e.key === 'Enter' && handleTrackPackage()}
              />
              <Button onClick={handleTrackPackage} className="bg-primary hover:bg-primary-dark">
                <Search className="h-4 w-4 mr-2" />
                Track
              </Button>
            </div>
          </div>

          {/* Search Result */}
          {searchResult && (
            <Card className="border-2 border-primary/20">
              <CardHeader className="pb-4">
                <div className="flex items-center justify-between">
                  <CardTitle className="text-lg">Package Details</CardTitle>
                  <Button onClick={handlePrint} variant="outline" size="sm">
                    <Printer className="h-4 w-4 mr-2" />
                    Print
                  </Button>
                </div>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Tracking Number</p>
                    <p className="font-medium">{searchResult.trackingNumber}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Status</p>
                    <StatusBadge status={searchResult.status} />
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Sender</p>
                    <p className="font-medium">{searchResult.senderName}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Receiver</p>
                    <p className="font-medium">{searchResult.receiverName}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">From</p>
                    <p className="font-medium flex items-center gap-1">
                      <MapPin className="h-4 w-4" />
                      {searchResult.pickupCity}
                    </p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">To</p>
                    <p className="font-medium flex items-center gap-1">
                      <MapPin className="h-4 w-4" />
                      {searchResult.deliveryCity}
                    </p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Type</p>
                    <p className="font-medium">{searchResult.courierType}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Weight</p>
                    <p className="font-medium">{searchResult.weight} kg</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Created Date</p>
                    <p className="font-medium flex items-center gap-1">
                      <Calendar className="h-4 w-4" />
                      {new Date(searchResult.createdAt).toLocaleDateString()}
                    </p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Delivery Date</p>
                    <p className="font-medium flex items-center gap-1">
                      <Calendar className="h-4 w-4" />
                      {searchResult.deliveryDate || 'TBD'}
                    </p>
                  </div>
                </div>
              </CardContent>
            </Card>
          )}
        </CardContent>
      </Card>

      {/* Recent Packages */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Package className="h-5 w-5" />
            My Recent Packages
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            {userPackages.map((courier) => (
              <div key={courier.id} className="flex items-center justify-between p-4 border rounded-lg hover:bg-muted/50 transition-colors">
                <div className="flex-1">
                  <div className="flex items-center gap-3">
                    <div className="font-medium">{courier.trackingNumber}</div>
                    <StatusBadge status={courier.status} />
                  </div>
                  <div className="text-sm text-muted-foreground mt-1">
                    {courier.pickupCity} → {courier.deliveryCity}
                  </div>
                  <div className="text-xs text-muted-foreground">
                    Type: {courier.courierType} • Weight: {courier.weight} kg
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
    </div>
  );
}