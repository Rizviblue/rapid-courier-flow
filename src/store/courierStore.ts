import { create } from 'zustand';

export type CourierStatus = 'pending' | 'in_transit' | 'delivered' | 'cancelled';

export interface Courier {
  id: string;
  trackingNumber: string;
  senderName: string;
  receiverName: string;
  pickupCity: string;
  deliveryCity: string;
  courierType: string;
  weight: number;
  deliveryDate: string;
  status: CourierStatus;
  createdBy: string;
  createdAt: string;
}

interface CourierState {
  couriers: Courier[];
  addCourier: (courier: Omit<Courier, 'id' | 'trackingNumber' | 'createdAt'>) => void;
  updateCourier: (id: string, updates: Partial<Courier>) => void;
  deleteCourier: (id: string) => void;
  getCourierStats: () => {
    total: number;
    inTransit: number;
    delivered: number;
    cancelled: number;
  };
}

// Demo data
const initialCouriers: Courier[] = [
  {
    id: '1',
    trackingNumber: 'CMS001234',
    senderName: 'John Doe',
    receiverName: 'Alice Smith',
    pickupCity: 'New York',
    deliveryCity: 'Los Angeles',
    courierType: 'Express',
    weight: 2.5,
    deliveryDate: '2024-01-25',
    status: 'in_transit',
    createdBy: '1',
    createdAt: '2024-01-20T10:00:00Z'
  },
  {
    id: '2',
    trackingNumber: 'CMS001235',
    senderName: 'Bob Johnson',
    receiverName: 'Carol Brown',
    pickupCity: 'Chicago',
    deliveryCity: 'Miami',
    courierType: 'Standard',
    weight: 1.2,
    deliveryDate: '2024-01-24',
    status: 'delivered',
    createdBy: '2',
    createdAt: '2024-01-19T14:30:00Z'
  },
  {
    id: '3',
    trackingNumber: 'CMS001236',
    senderName: 'David Wilson',
    receiverName: 'Eva Davis',
    pickupCity: 'Houston',
    deliveryCity: 'Seattle',
    courierType: 'Express',
    weight: 3.8,
    deliveryDate: '2024-01-26',
    status: 'pending',
    createdBy: '1',
    createdAt: '2024-01-21T09:15:00Z'
  },
  {
    id: '4',
    trackingNumber: 'CMS001237',
    senderName: 'Frank Miller',
    receiverName: 'Grace Lee',
    pickupCity: 'Phoenix',
    deliveryCity: 'Boston',
    courierType: 'Standard',
    weight: 0.8,
    deliveryDate: '2024-01-23',
    status: 'cancelled',
    createdBy: '2',
    createdAt: '2024-01-18T16:45:00Z'
  }
];

function generateTrackingNumber(): string {
  const prefix = 'CMS';
  const number = Math.floor(100000 + Math.random() * 900000);
  return `${prefix}${number}`;
}

export const useCourierStore = create<CourierState>((set, get) => ({
  couriers: initialCouriers,
  
  addCourier: (courierData) => {
    const newCourier: Courier = {
      ...courierData,
      id: Date.now().toString(),
      trackingNumber: generateTrackingNumber(),
      createdAt: new Date().toISOString()
    };
    
    set((state) => ({
      couriers: [newCourier, ...state.couriers]
    }));
  },
  
  updateCourier: (id, updates) => {
    set((state) => ({
      couriers: state.couriers.map(courier =>
        courier.id === id ? { ...courier, ...updates } : courier
      )
    }));
  },
  
  deleteCourier: (id) => {
    set((state) => ({
      couriers: state.couriers.filter(courier => courier.id !== id)
    }));
  },
  
  getCourierStats: () => {
    const { couriers } = get();
    return {
      total: couriers.length,
      inTransit: couriers.filter(c => c.status === 'in_transit').length,
      delivered: couriers.filter(c => c.status === 'delivered').length,
      cancelled: couriers.filter(c => c.status === 'cancelled').length
    };
  }
}));