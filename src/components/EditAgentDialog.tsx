import { useState, useEffect } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toast } from '@/hooks/use-toast';

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

interface EditAgentDialogProps {
  agent: Agent | null;
  isOpen: boolean;
  onClose: () => void;
  onSave: (agent: Agent) => void;
}

export default function EditAgentDialog({ agent, isOpen, onClose, onSave }: EditAgentDialogProps) {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    city: '',
    status: 'active' as 'active' | 'inactive'
  });

  useEffect(() => {
    if (agent) {
      setFormData({
        name: agent.name,
        email: agent.email,
        phone: agent.phone,
        city: agent.city,
        status: agent.status
      });
    }
  }, [agent]);

  const handleSave = () => {
    if (!formData.name || !formData.email || !formData.city) {
      toast({
        title: "Validation Error",
        description: "Please fill in all required fields.",
        variant: "destructive"
      });
      return;
    }

    if (agent) {
      const updatedAgent: Agent = {
        ...agent,
        ...formData
      };
      onSave(updatedAgent);
      toast({
        title: "Agent Updated",
        description: `${formData.name} has been updated successfully.`,
      });
    }
    onClose();
  };

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Edit Agent Details</DialogTitle>
        </DialogHeader>
        <div className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="edit-name">Full Name *</Label>
            <Input
              id="edit-name"
              value={formData.name}
              onChange={(e) => setFormData({...formData, name: e.target.value})}
              placeholder="Enter agent's full name"
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="edit-email">Email *</Label>
            <Input
              id="edit-email"
              type="email"
              value={formData.email}
              onChange={(e) => setFormData({...formData, email: e.target.value})}
              placeholder="Enter email address"
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="edit-phone">Phone Number</Label>
            <Input
              id="edit-phone"
              value={formData.phone}
              onChange={(e) => setFormData({...formData, phone: e.target.value})}
              placeholder="Enter phone number"
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="edit-city">City *</Label>
            <Select value={formData.city} onValueChange={(value) => setFormData({...formData, city: value})}>
              <SelectTrigger>
                <SelectValue placeholder="Select city" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="New York">New York</SelectItem>
                <SelectItem value="Los Angeles">Los Angeles</SelectItem>
                <SelectItem value="Chicago">Chicago</SelectItem>
                <SelectItem value="Houston">Houston</SelectItem>
                <SelectItem value="Phoenix">Phoenix</SelectItem>
              </SelectContent>
            </Select>
          </div>
          <div className="space-y-2">
            <Label htmlFor="edit-status">Status</Label>
            <Select value={formData.status} onValueChange={(value: 'active' | 'inactive') => setFormData({...formData, status: value})}>
              <SelectTrigger>
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="active">Active</SelectItem>
                <SelectItem value="inactive">Inactive</SelectItem>
              </SelectContent>
            </Select>
          </div>
          <div className="flex justify-end gap-2">
            <Button variant="outline" onClick={onClose}>
              Cancel
            </Button>
            <Button onClick={handleSave}>Save Changes</Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
}