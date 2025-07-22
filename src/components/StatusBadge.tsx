import { Badge } from '@/components/ui/badge';
import { CourierStatus } from '@/store/courierStore';
import { cn } from '@/lib/utils';

interface StatusBadgeProps {
  status: CourierStatus;
  className?: string;
}

const statusConfig = {
  pending: {
    label: 'Pending',
    className: 'bg-status-pending text-white'
  },
  in_transit: {
    label: 'In Transit',
    className: 'bg-status-transit text-white'
  },
  delivered: {
    label: 'Delivered',
    className: 'bg-status-delivered text-white'
  },
  cancelled: {
    label: 'Cancelled',
    className: 'bg-status-cancelled text-white'
  }
};

export default function StatusBadge({ status, className }: StatusBadgeProps) {
  const config = statusConfig[status];
  
  return (
    <Badge className={cn(config.className, className)}>
      {config.label}
    </Badge>
  );
}