import numpy as np
from matplotlib import pyplot as plt
%matplotlib notebook
from skimage.io import imread
im = imread(r'C:\Users\LENOVO\Desktop\perio.png')

plt.figure()
plt.imshow(im, cmap=plt.cm.gray)
plt.axis('off')
plt.show()
def otsu_threshold(h):
    h = h*1./h.sum() # Probability histogram
    
    # Check every possible threshold
    var_within = np.zeros(len(h))
    var_between = np.zeros(len(h))
    sep = np.zeros(len(h))
    
    for t in range(1,len(h)-1):
        # Class probabilities
        w1 = h[:t].sum()
        w2 = h[t:].sum()
        # Class means
        m1 = (np.arange(0,t)*h[:t]).sum()/w1
        m2 = (np.arange(t,len(h))*h[t:]).sum()/w2
        # Class variances
        s1 = (((np.arange(0,t)-m1)**2)*h[:t]).sum()/w1
        s2 = (((np.arange(t,len(h))-m2)**2)*h[t:]).sum()/w2
        # Intra-class
        sw = w1*s1+w2*s2
        # Inter-class
        sb = w1*w2*((m2-m1)**2)
        # Separability
        var_within[t] = sw
        var_between[t] = sb
    
    sep[1:-1] = var_between[1:-1]/var_within[1:-1]
    best_t = np.argmax(sep)
    
    return best_t, var_within, var_between, sep
  
  h,bins = np.histogram(im.flatten(),range(257))
plt.figure()
plt.bar(bins[:-1],h,bins[1]-bins[0])
plt.show()

t,vw,vb,sep = otsu_threshold(h)
print("Otsu threshold : %d"%t)

plt.figure(figsize=(10,5))
plt.subplot(1,2,1)
plt.imshow(im, cmap=plt.cm.gray, vmin=0, vmax=255)
plt.subplot(1,2,2)
plt.imshow(im>t, cmap=plt.cm.gray)

plt.figure()
plt.subplot(2,1,1)
plt.plot(vw, 'b-')
plt.plot(vb, 'r-')
plt.title('Variance intra (blue) and between (red) class')
plt.subplot(2,1,2)
plt.plot(sep, 'k-')
plt.title('Separability')
plt.show()
