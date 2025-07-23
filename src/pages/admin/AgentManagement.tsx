import { useState } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Search, Plus, Edit, Trash2, User, Mail, Phone, MapPin, Eye } from 'lucide-react';
import { toast } from '@/hooks/use-toast';
import EditAgentDialog from '@/components/EditAgentDialog';
import ViewAgentDialog from '@/components/ViewAgentDialog';

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

const initialAgents: Agent[] = [
  {
    id: '1',
    name: 'Sarah Johnson',
    email: 'sarah.johnson@courierpro.com',
    phone: '+1 234 567 8901',
    city: 'New York',
    status: 'active',
    totalCouriers: 156,
    joinedDate: '2023-01-15'
  },
  {
    id: '2',
    name: 'Michael Chen',
    email: 'michael.chen@courierpro.com',
    phone: '+1 234 567 8902',
    city: 'Los Angeles',
    status: 'active',
    totalCouriers: 134,
    joinedDate: '2023-02-20'
  },
  {
    id: '3',
    name: 'Emily Rodriguez',
    email: 'emily.rodriguez@courierpro.com',
    phone: '+1 234 567 8903',
    city: 'Chicago',
    status: 'inactive',
    totalCouriers: 89,
    joinedDate: '2023-03-10'
  },
  {
    id: '4',
    name: 'David Kim',
    email: 'david.kim@courierpro.com',
    phone: '+1 234 567 8904',
    city: 'Houston',
    status: 'active',
    totalCouriers: 201,
    joinedDate: '2022-11-05'
  }
];

export default function AgentManagement() {
  const [agents, setAgents] = useState<Agent[]>(initialAgents);
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState('all');
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);
  const [editingAgent, setEditingAgent] = useState<Agent | null>(null);
  const [viewingAgent, setViewingAgent] = useState<Agent | null>(null);
  const [newAgent, setNewAgent] = useState({
    name: '',
    email: '',
    phone: '',
    city: '',
    status: 'active' as 'active' | 'inactive'
  });

  const filteredAgents = agents.filter(agent => {
    const matchesSearch = 
      agent.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      agent.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
      agent.city.toLowerCase().includes(searchTerm.toLowerCase());
    
    const matchesStatus = statusFilter === 'all' || agent.status === statusFilter;
    
    return matchesSearch && matchesStatus;
  });

  const handleAddAgent = () => {
    if (!newAgent.name || !newAgent.email || !newAgent.city) {
      toast({
        title: "Validation Error",
        description: "Please fill in all required fields.",
        variant: "destructive"
      });
      return;
    }

    const agent: Agent = {
      id: Date.now().toString(),
      ...newAgent,
      totalCouriers: 0,
      joinedDate: new Date().toISOString().split('T')[0]
    };

    setAgents([...agents, agent]);
    setNewAgent({ name: '', email: '', phone: '', city: '', status: 'active' });
    setIsAddDialogOpen(false);
    
    toast({
      title: "Agent Added",
      description: `${agent.name} has been added successfully.`,
    });
  };

  const handleDeleteAgent = (id: string, name: string) => {
    if (window.confirm(`Are you sure you want to delete agent ${name}?`)) {
      setAgents(agents.filter(agent => agent.id !== id));
      toast({
        title: "Agent Deleted",
        description: `${name} has been removed from the system.`,
      });
    }
  };

  const handleEditAgent = (agent: Agent) => {
    setEditingAgent(agent);
  };

  const handleSaveEdit = (updatedAgent: Agent) => {
    setAgents(agents.map(agent => 
      agent.id === updatedAgent.id ? updatedAgent : agent
    ));
    setEditingAgent(null);
  };

  const handleViewAgent = (agent: Agent) => {
    setViewingAgent(agent);
  };

  const toggleAgentStatus = (id: string) => {
    setAgents(agents.map(agent => 
      agent.id === id 
        ? { ...agent, status: agent.status === 'active' ? 'inactive' : 'active' }
        : agent
    ));
    toast({
      title: "Status Updated",
      description: "Agent status has been changed successfully.",
    });
  };

  const stats = {
    total: agents.length,
    active: agents.filter(a => a.status === 'active').length,
    inactive: agents.filter(a => a.status === 'inactive').length,
    totalCouriers: agents.reduce((sum, agent) => sum + agent.totalCouriers, 0)
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">Agent Management</h1>
          <p className="text-muted-foreground">Manage and monitor your delivery agents</p>
        </div>
        <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
          <DialogTrigger asChild>
            <Button className="bg-primary hover:bg-primary-dark">
              <Plus className="h-4 w-4 mr-2" />
              Add New Agent
            </Button>
          </DialogTrigger>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Add New Agent</DialogTitle>
            </DialogHeader>
            <div className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="name">Full Name *</Label>
                <Input
                  id="name"
                  value={newAgent.name}
                  onChange={(e) => setNewAgent({...newAgent, name: e.target.value})}
                  placeholder="Enter agent's full name"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="email">Email *</Label>
                <Input
                  id="email"
                  type="email"
                  value={newAgent.email}
                  onChange={(e) => setNewAgent({...newAgent, email: e.target.value})}
                  placeholder="Enter email address"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="phone">Phone Number</Label>
                <Input
                  id="phone"
                  value={newAgent.phone}
                  onChange={(e) => setNewAgent({...newAgent, phone: e.target.value})}
                  placeholder="Enter phone number"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="city">City *</Label>
                <Select value={newAgent.city} onValueChange={(value) => setNewAgent({...newAgent, city: value})}>
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
              <div className="flex justify-end gap-2">
                <Button variant="outline" onClick={() => setIsAddDialogOpen(false)}>
                  Cancel
                </Button>
                <Button onClick={handleAddAgent}>Add Agent</Button>
              </div>
            </div>
          </DialogContent>
        </Dialog>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Total Agents</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold">{stats.total}</div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Active Agents</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold text-green-600">{stats.active}</div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Inactive Agents</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold text-red-600">{stats.inactive}</div>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Total Couriers</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold">{stats.totalCouriers}</div>
          </CardContent>
        </Card>
      </div>

      {/* Filters */}
      <Card>
        <CardHeader>
          <CardTitle>Filters</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="flex flex-col sm:flex-row gap-4">
            <div className="flex-1">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4" />
                <Input
                  placeholder="Search by name, email, or city..."
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
                variant={statusFilter === 'active' ? 'default' : 'outline'}
                size="sm"
                onClick={() => setStatusFilter('active')}
              >
                Active
              </Button>
              <Button
                variant={statusFilter === 'inactive' ? 'default' : 'outline'}
                size="sm"
                onClick={() => setStatusFilter('inactive')}
              >
                Inactive
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Agents Table */}
      <Card>
        <CardHeader>
          <CardTitle>Agents ({filteredAgents.length})</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="overflow-x-auto">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Agent</TableHead>
                  <TableHead>Contact</TableHead>
                  <TableHead>City</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Couriers</TableHead>
                  <TableHead>Joined</TableHead>
                  <TableHead>Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {filteredAgents.map((agent) => (
                  <TableRow key={agent.id}>
                    <TableCell>
                      <div className="flex items-center gap-3">
                        <div className="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                          <User className="h-5 w-5 text-primary-foreground" />
                        </div>
                        <div>
                          <div className="font-medium">{agent.name}</div>
                          <div className="text-sm text-muted-foreground">ID: {agent.id}</div>
                        </div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <div className="space-y-1">
                        <div className="flex items-center gap-1 text-sm">
                          <Mail className="h-3 w-3" />
                          {agent.email}
                        </div>
                        <div className="flex items-center gap-1 text-sm">
                          <Phone className="h-3 w-3" />
                          {agent.phone}
                        </div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-1">
                        <MapPin className="h-4 w-4 text-muted-foreground" />
                        {agent.city}
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge 
                        variant={agent.status === 'active' ? 'default' : 'secondary'}
                        className={agent.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}
                      >
                        {agent.status}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      <Badge variant="outline">{agent.totalCouriers}</Badge>
                    </TableCell>
                    <TableCell>
                      {new Date(agent.joinedDate).toLocaleDateString()}
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => handleViewAgent(agent)}
                          title="View Details"
                        >
                          <Eye className="h-4 w-4" />
                        </Button>
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => handleEditAgent(agent)}
                          title="Edit Agent"
                        >
                          <Edit className="h-4 w-4" />
                        </Button>
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => toggleAgentStatus(agent.id)}
                          title={agent.status === 'active' ? 'Deactivate' : 'Activate'}
                        >
                          {agent.status === 'active' ? 'Deactivate' : 'Activate'}
                        </Button>
                        <Button 
                          size="sm" 
                          variant="outline"
                          onClick={() => handleDeleteAgent(agent.id, agent.name)}
                          className="text-red-600 hover:text-red-700"
                          title="Delete Agent"
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
        </CardContent>
      </Card>

      <EditAgentDialog
        agent={editingAgent}
        isOpen={!!editingAgent}
        onClose={() => setEditingAgent(null)}
        onSave={handleSaveEdit}
      />

      <ViewAgentDialog
        agent={viewingAgent}
        isOpen={!!viewingAgent}
        onClose={() => setViewingAgent(null)}
      />
    </div>
  );
}