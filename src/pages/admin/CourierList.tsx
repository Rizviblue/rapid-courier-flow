import { useState } from 'react';
import { Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { useCourierStore } from '@/store/courierStore';
import StatusBadge from '@/components/StatusBadge';
import { Search, Plus, Edit, Trash2, Eye, Filter } from 'lucide-react';
import { toast } from '@/hooks/use-toast';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';

export default function CourierList() {
  const { couriers, deleteCourier } = useCourierStore();
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState('all');
  const [selectedCourier, setSelectedCourier] = useState<any>(null);
  const [viewDialogOpen, setViewDialogOpen] = useState(false);
  const [editDialogOpen, setEditDialogOpen] = useState(false);

  // Filter couriers based on search term and status
  const filteredCouriers = couriers.filter(courier => {
    const matchesSearch = 
      courier.trackingNumber.toLowerCase().includes(searchTerm.toLowerCase()) ||
      courier.senderName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      courier.receiverName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      courier.pickupCity.toLowerCase().includes(searchTerm.toLowerCase()) ||
      courier.deliveryCity.toLowerCase().includes(searchTerm.toLowerCase());
    
    const matchesStatus = statusFilter === 'all' || courier.status === statusFilter;
    
    return matchesSearch && matchesStatus;
  });

  const handleDelete = (id: string, trackingNumber: string) => {
    if (window.confirm(`Are you sure you want to delete courier ${trackingNumber}?`)) {
      deleteCourier(id);
      toast({
        title: "Courier Deleted",
        description: `Courier ${trackingNumber} has been deleted successfully.`,
      });
    }
  };

  const handleView = (courier: any) => {
    setSelectedCourier(courier);
    setViewDialogOpen(true);
  };

  const handleEdit = (courier: any) => {
    setSelectedCourier(courier);
    setEditDialogOpen(true);
    toast({
      title: "Edit Courier",
      description: `Opening edit form for ${courier.trackingNumber}`,
    });
  };

  return (
    <>
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">Courier List</h1>
          <p className="text-muted-foreground">Manage all courier shipments</p>
        </div>
        <Button className="bg-primary hover:bg-primary-dark" asChild>
          <Link to="/admin/add-courier">
            <Plus className="h-4 w-4 mr-2" />
            Add New Courier
          </Link>
        </Button>
      </div>

      {/* Filters */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Filter className="h-5 w-5" />
            Filters
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="flex flex-col sm:flex-row gap-4">
            <div className="flex-1">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4" />
                <Input
                  placeholder="Search by tracking number, sender, receiver, or city..."
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  className="pl-10"
                />
              </div>
            </div>
            <div className="flex gap-2">
              <Button
                variant={statusFilter === 'all' ? 'default' : 'outline'}
                size="sm"
                onClick={() => setStatusFilter('all')}
              >
                All
              </Button>
              <Button
                variant={statusFilter === 'pending' ? 'default' : 'outline'}
                size="sm"
                onClick={() => setStatusFilter('pending')}
              >
                Pending
              </Button>
              <Button
                variant={statusFilter === 'in_transit' ? 'default' : 'outline'}
                size="sm"
                onClick={() => setStatusFilter('in_transit')}
              >
                In Transit
              </Button>
              <Button
                variant={statusFilter === 'delivered' ? 'default' : 'outline'}
                size="sm"
                onClick={() => setStatusFilter('delivered')}
              >
                Delivered
              </Button>
              <Button
                variant={statusFilter === 'cancelled' ? 'default' : 'outline'}
                size="sm"
                onClick={() => setStatusFilter('cancelled')}
              >
                Cancelled
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Couriers Table */}
      <Card>
        <CardHeader>
          <CardTitle>
            Couriers ({filteredCouriers.length})
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div className="overflow-x-auto">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Tracking Number</TableHead>
                  <TableHead>Sender</TableHead>
                  <TableHead>Receiver</TableHead>
                  <TableHead>Route</TableHead>
                  <TableHead>Type</TableHead>
                  <TableHead>Weight</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Date</TableHead>
                  <TableHead>Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {filteredCouriers.map((courier) => (
                  <TableRow key={courier.id}>
                    <TableCell className="font-medium">
                      {courier.trackingNumber}
                    </TableCell>
                    <TableCell>{courier.senderName}</TableCell>
                    <TableCell>{courier.receiverName}</TableCell>
                    <TableCell>
                      <div className="text-sm">
                        <div>{courier.pickupCity}</div>
                        <div className="text-muted-foreground">→ {courier.deliveryCity}</div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="secondary">{courier.courierType}</Badge>
                    </TableCell>
                    <TableCell>{courier.weight} kg</TableCell>
                    <TableCell>
                      <StatusBadge status={courier.status} />
                    </TableCell>
                    <TableCell>
                      {new Date(courier.createdAt).toLocaleDateString()}
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => handleView(courier)}
                        >
                          <Eye className="h-4 w-4" />
                        </Button>
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => handleEdit(courier)}
                        >
                          <Edit className="h-4 w-4" />
                        </Button>
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => handleDelete(courier.id, courier.trackingNumber)}
                          className="text-red-600 hover:text-red-700"
                        >
                          <Trash2 className="h-4 w-4" />
                        </Button>
                      </div>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>

          {filteredCouriers.length === 0 && (
            <div className="text-center py-8 text-muted-foreground">
              {searchTerm || statusFilter !== 'all' 
                ? 'No couriers found matching your criteria.' 
                : 'No couriers found. Add your first courier to get started.'}
            </div>
          )}
        </CardContent>
      </Card>
    </div>

    {/* View Courier Dialog */}
    <Dialog open={viewDialogOpen} onOpenChange={setViewDialogOpen}>
      <DialogContent className="max-w-2xl">
        <DialogHeader>
          <DialogTitle>Courier Details</DialogTitle>
        </DialogHeader>
        {selectedCourier && (
          <div className="grid grid-cols-2 gap-4">
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Tracking Number</Label>
              <p className="font-medium">{selectedCourier.trackingNumber}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Status</Label>
              <div className="mt-1">
                <StatusBadge status={selectedCourier.status} />
              </div>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Sender</Label>
              <p className="font-medium">{selectedCourier.senderName}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Receiver</Label>
              <p className="font-medium">{selectedCourier.receiverName}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">From</Label>
              <p className="font-medium">{selectedCourier.pickupCity}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">To</Label>
              <p className="font-medium">{selectedCourier.deliveryCity}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Type</Label>
              <p className="font-medium">{selectedCourier.courierType}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Weight</Label>
              <p className="font-medium">{selectedCourier.weight} kg</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Created Date</Label>
              <p className="font-medium">{new Date(selectedCourier.createdAt).toLocaleDateString()}</p>
            </div>
            <div>
              <Label className="text-sm font-medium text-muted-foreground">Delivery Date</Label>
              <p className="font-medium">{selectedCourier.deliveryDate || 'TBD'}</p>
            </div>
          </div>
        )}
      </DialogContent>
    </Dialog>

    {/* Edit Courier Dialog */}
    <Dialog open={editDialogOpen} onOpenChange={setEditDialogOpen}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Edit Courier</DialogTitle>
        </DialogHeader>
        <div className="space-y-4">
          <p className="text-sm text-muted-foreground">
            Edit functionality would be implemented here with a form similar to AddCourier.
          </p>
          <Button onClick={() => setEditDialogOpen(false)}>
            Close
          </Button>
        </div>
      </DialogContent>
    </Dialog>
    </>
  );
}