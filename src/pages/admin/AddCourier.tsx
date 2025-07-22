import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useCourierStore, CourierStatus } from '@/store/courierStore';
import { useAuthStore } from '@/store/authStore';
import { toast } from '@/hooks/use-toast';
import { ArrowLeft, Package } from 'lucide-react';

export default function AddCourier() {
  const navigate = useNavigate();
  const { addCourier } = useCourierStore();
  const { user } = useAuthStore();
  
  const [formData, setFormData] = useState({
    senderName: '',
    senderPhone: '',
    senderAddress: '',
    receiverName: '',
    receiverPhone: '',
    receiverAddress: '',
    pickupCity: '',
    deliveryCity: '',
    courierType: '',
    weight: '',
    deliveryDate: '',
    status: 'pending' as CourierStatus,
    notes: ''
  });

  const handleInputChange = (field: string, value: string) => {
    setFormData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!formData.senderName || !formData.receiverName || !formData.pickupCity || !formData.deliveryCity) {
      toast({
        title: "Validation Error",
        description: "Please fill in all required fields.",
        variant: "destructive"
      });
      return;
    }

    addCourier({
      senderName: formData.senderName,
      receiverName: formData.receiverName,
      pickupCity: formData.pickupCity,
      deliveryCity: formData.deliveryCity,
      courierType: formData.courierType,
      weight: parseFloat(formData.weight) || 0,
      deliveryDate: formData.deliveryDate,
      status: formData.status,
      createdBy: user?.id || '1'
    });

    toast({
      title: "Success",
      description: "Courier has been added successfully!",
    });

    navigate('/admin/couriers');
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center gap-4">
        <Button variant="outline" size="sm" onClick={() => navigate(-1)}>
          <ArrowLeft className="h-4 w-4 mr-2" />
          Back
        </Button>
        <div>
          <h1 className="text-3xl font-bold text-foreground">Add New Courier</h1>
          <p className="text-muted-foreground">Create a new courier shipment</p>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="max-w-4xl">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          {/* Sender Information */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Package className="h-5 w-5" />
                Sender Information
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="senderName">Sender Name *</Label>
                <Input
                  id="senderName"
                  value={formData.senderName}
                  onChange={(e) => handleInputChange('senderName', e.target.value)}
                  placeholder="Enter sender's full name"
                  required
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="senderPhone">Sender Phone</Label>
                <Input
                  id="senderPhone"
                  value={formData.senderPhone}
                  onChange={(e) => handleInputChange('senderPhone', e.target.value)}
                  placeholder="Enter phone number"
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="senderAddress">Sender Address</Label>
                <Textarea
                  id="senderAddress"
                  value={formData.senderAddress}
                  onChange={(e) => handleInputChange('senderAddress', e.target.value)}
                  placeholder="Enter complete address"
                  rows={3}
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="pickupCity">Pickup City *</Label>
                <Select value={formData.pickupCity} onValueChange={(value) => handleInputChange('pickupCity', value)}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select pickup city" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="new-york">New York</SelectItem>
                    <SelectItem value="los-angeles">Los Angeles</SelectItem>
                    <SelectItem value="chicago">Chicago</SelectItem>
                    <SelectItem value="houston">Houston</SelectItem>
                    <SelectItem value="phoenix">Phoenix</SelectItem>
                    <SelectItem value="philadelphia">Philadelphia</SelectItem>
                    <SelectItem value="san-antonio">San Antonio</SelectItem>
                    <SelectItem value="san-diego">San Diego</SelectItem>
                    <SelectItem value="dallas">Dallas</SelectItem>
                    <SelectItem value="san-jose">San Jose</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </CardContent>
          </Card>

          {/* Receiver Information */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Package className="h-5 w-5" />
                Receiver Information
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="receiverName">Receiver Name *</Label>
                <Input
                  id="receiverName"
                  value={formData.receiverName}
                  onChange={(e) => handleInputChange('receiverName', e.target.value)}
                  placeholder="Enter receiver's full name"
                  required
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="receiverPhone">Receiver Phone</Label>
                <Input
                  id="receiverPhone"
                  value={formData.receiverPhone}
                  onChange={(e) => handleInputChange('receiverPhone', e.target.value)}
                  placeholder="Enter phone number"
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="receiverAddress">Receiver Address</Label>
                <Textarea
                  id="receiverAddress"
                  value={formData.receiverAddress}
                  onChange={(e) => handleInputChange('receiverAddress', e.target.value)}
                  placeholder="Enter complete address"
                  rows={3}
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="deliveryCity">Delivery City *</Label>
                <Select value={formData.deliveryCity} onValueChange={(value) => handleInputChange('deliveryCity', value)}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select delivery city" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="new-york">New York</SelectItem>
                    <SelectItem value="los-angeles">Los Angeles</SelectItem>
                    <SelectItem value="chicago">Chicago</SelectItem>
                    <SelectItem value="houston">Houston</SelectItem>
                    <SelectItem value="phoenix">Phoenix</SelectItem>
                    <SelectItem value="philadelphia">Philadelphia</SelectItem>
                    <SelectItem value="san-antonio">San Antonio</SelectItem>
                    <SelectItem value="san-diego">San Diego</SelectItem>
                    <SelectItem value="dallas">Dallas</SelectItem>
                    <SelectItem value="san-jose">San Jose</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </CardContent>
          </Card>
        </div>

        {/* Package Details */}
        <Card className="mt-6">
          <CardHeader>
            <CardTitle>Package Details</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div className="space-y-2">
                <Label htmlFor="courierType">Courier Type</Label>
                <Select value={formData.courierType} onValueChange={(value) => handleInputChange('courierType', value)}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select type" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="standard">Standard</SelectItem>
                    <SelectItem value="express">Express</SelectItem>
                    <SelectItem value="overnight">Overnight</SelectItem>
                    <SelectItem value="same-day">Same Day</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div className="space-y-2">
                <Label htmlFor="weight">Weight (kg)</Label>
                <Input
                  id="weight"
                  type="number"
                  step="0.1"
                  value={formData.weight}
                  onChange={(e) => handleInputChange('weight', e.target.value)}
                  placeholder="0.0"
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="deliveryDate">Delivery Date</Label>
                <Input
                  id="deliveryDate"
                  type="date"
                  value={formData.deliveryDate}
                  onChange={(e) => handleInputChange('deliveryDate', e.target.value)}
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="status">Status</Label>
                <Select value={formData.status} onValueChange={(value) => handleInputChange('status', value as CourierStatus)}>
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="pending">Pending</SelectItem>
                    <SelectItem value="in_transit">In Transit</SelectItem>
                    <SelectItem value="delivered">Delivered</SelectItem>
                    <SelectItem value="cancelled">Cancelled</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>

            <div className="mt-4 space-y-2">
              <Label htmlFor="notes">Additional Notes</Label>
              <Textarea
                id="notes"
                value={formData.notes}
                onChange={(e) => handleInputChange('notes', e.target.value)}
                placeholder="Any additional information about the shipment..."
                rows={3}
              />
            </div>
          </CardContent>
        </Card>

        {/* Actions */}
        <div className="flex justify-end gap-4 mt-6">
          <Button type="button" variant="outline" onClick={() => navigate(-1)}>
            Cancel
          </Button>
          <Button type="submit" className="bg-primary hover:bg-primary-dark">
            Create Courier
          </Button>
        </div>
      </form>
    </div>
  );
}