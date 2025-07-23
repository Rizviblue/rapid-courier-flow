import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import Login from "./components/Login";
import Register from "./pages/Register";
import SupportContact from "./pages/SupportContact";
import Analytics from "./pages/Analytics";
import ProtectedRoute from "./components/ProtectedRoute";
import AdminLayout from "./pages/admin/AdminLayout";
import AdminDashboard from "./pages/admin/AdminDashboard";
import AddCourier from "./pages/admin/AddCourier";
import CourierList from "./pages/admin/CourierList";
import AgentManagement from "./pages/admin/AgentManagement";
import CustomerManagement from "./pages/admin/CustomerManagement";
import Reports from "./pages/admin/Reports";
import AdminSettings from "./pages/admin/Settings";
import AgentLayout from "./pages/agent/AgentLayout";
import AgentDashboard from "./pages/agent/AgentDashboard";
import AgentReports from "./pages/agent/AgentReports";
import AgentSettings from "./pages/agent/AgentSettings";
import UserLayout from "./pages/user/UserLayout";
import UserDashboard from "./pages/user/UserDashboard";
import UserPackages from "./pages/user/UserPackages";
import UserSettings from "./pages/user/UserSettings";
import NotFoundPage from "./pages/NotFoundPage";
import UnauthorizedPage from "./pages/UnauthorizedPage";

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <Routes>
          {/* Public Routes */}
          <Route path="/" element={<Navigate to="/login" replace />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/support" element={<SupportContact />} />
          <Route path="/unauthorized" element={<UnauthorizedPage />} />
          
          {/* Admin Routes */}
          <Route path="/admin" element={
            <ProtectedRoute allowedRoles={['admin']}>
              <AdminLayout />
            </ProtectedRoute>
          }>
            <Route path="dashboard" element={<AdminDashboard />} />
            <Route path="add-courier" element={<AddCourier />} />
            <Route path="couriers" element={<CourierList />} />
            <Route path="agents" element={<AgentManagement />} />
            <Route path="customers" element={<CustomerManagement />} />
            <Route path="reports" element={<Reports />} />
            <Route path="analytics" element={<Analytics />} />
            <Route path="settings" element={<AdminSettings />} />
          </Route>
          
          {/* Agent Routes */}
          <Route path="/agent" element={
            <ProtectedRoute allowedRoles={['agent']}>
              <AgentLayout />
            </ProtectedRoute>
          }>
            <Route path="dashboard" element={<AgentDashboard />} />
            <Route path="add-courier" element={<AddCourier />} />
            <Route path="couriers" element={<CourierList />} />
            <Route path="reports" element={<AgentReports />} />
            <Route path="settings" element={<AgentSettings />} />
          </Route>
          
          {/* User Routes */}
          <Route path="/user" element={
            <ProtectedRoute allowedRoles={['user']}>
              <UserLayout />
            </ProtectedRoute>
          }>
            <Route path="dashboard" element={<UserDashboard />} />
            <Route path="track" element={<UserDashboard />} />
            <Route path="packages" element={<UserPackages />} />
            <Route path="support" element={<SupportContact />} />
            <Route path="settings" element={<UserSettings />} />
          </Route>
          
          {/* Catch-all route */}
          <Route path="*" element={<NotFoundPage />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
