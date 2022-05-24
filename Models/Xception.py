from google.colab import drive
drive.mount('/content/drive/', force_remount=True)
!rm -r /content/dataset
!cp -r /content/drive/MyDrive/DentalPanormicXray  /content/dataset


import keras,os
import numpy as np
import tensorflow as tf 
import pandas as pd
import matplotlib, cv2
import seaborn as sns
import matplotlib.pyplot as plt
import matplotlib.image as mpimg
import itertools
from tensorflow import keras
from tensorflow.keras.utils import plot_model,to_categorical
from tensorflow.keras.layers import Input, Conv2D, Activation, MaxPool2D, Dense, Dropout, Flatten, BatchNormalization, GlobalAveragePooling2D
from tensorflow.keras.optimizers import Adam,RMSprop
from tensorflow.keras.models import Sequential, Model
from tensorflow.keras.callbacks import EarlyStopping,ReduceLROnPlateau
from tensorflow.keras.metrics import categorical_crossentropy
from tensorflow.keras.preprocessing.image import ImageDataGenerator, img_to_array, load_img, array_to_img
from sklearn.metrics import classification_report,confusion_matrix,accuracy_score
from  matplotlib import pyplot as plt

%matplotlib inline

image_dir ='/content/dataset'
base_path = '/content/model_weights/'
perio_path='/content/dataset/Periodontal/'
non_perio_path='/content/dataset/Non-periodontal'

img_data = []
lbl_data = []

image_size = 128,128
batch_size =25 
epochs = 12

def listImagesFiles(image_dir):
    listOfFile = os.listdir(image_dir)
    allFiles = list()
    for entry in listOfFile:
        fullPath = os.path.join(image_dir, entry)
        if os.path.isdir(fullPath):
            allFiles = allFiles + listImagesFiles(fullPath)
        else:
            allFiles.append(fullPath)
    return allFiles

files=listImagesFiles(image_dir)
print(len(files))
img_data.clear()
lbl_data.clear()
def load_data():  
    for file in listImagesFiles(image_dir):
        img = load_image(file)
        if img is not None:
            img_data.append(img)
            lbl_data.append(os.path.basename(os.path.dirname(file)))
        else:
            continue
    return img_data,lbl_data

def load_image(file) :
    try:
        img = cv2.imread(file)
        img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        img = cv2.resize(img,image_size)
        return img
    except:
        return None

img_data,lbl_data = load_data()
print(len(img_data))
print(len(lbl_data))

def getClassesNum():
    classes=set(lbl_data)
    return classes._len_()

getClassesNum()

'''######################## Encoding images classes labels ########################'''
def categorizeLables(lbl_data):
    from sklearn.preprocessing import LabelEncoder
    from sklearn import preprocessing
    le = preprocessing.LabelEncoder()
    encoding = le.fit_transform(lbl_data)
    le_name_mapping = dict(zip(le.classes_, le.transform(le.classes_)))
    print(le_name_mapping)
    return encoding

categorizeLables(lbl_data)


def overview(total_rows):
        fig = plt.figure(figsize=(10,10))
        idx = 0
        while idx < total_rows:
            ax = fig.add_subplot(10,10,idx+1)
            ax.imshow(img_data[idx], cmap=plt.cm.get_cmap('gray'))
            plt.xticks(np.array([]))
            plt.yticks(np.array([]))
            plt.tight_layout()
            idx += 1
            
        #print(lbl_data)
        plt.show()

overview(20)
from tensorflow.python.ops.gen_math_ops import xlogy
'''######################## Loading the dataset ########################'''
def split_dataset(): 
    from sklearn.model_selection  import train_test_split
    #img_data,lbl_data = load_data()
    imgs = np.asarray(img_data)
   
    imgs = imgs / 255.0
    
    lbls= categorizeLables(lbl_data)
    lbls = np.asarray(lbls)
    lbls= to_categorical(lbls, num_classes=getClassesNum())

    x_train, x_test, y_train, y_test = train_test_split(imgs,lbls, test_size=0.20, random_state = 0) 
    return  x_train, x_test, y_train, y_test 

x_train, x_test, y_train, y_test  = split_dataset()
print('training data: ',len(x_train))
print('testing data: ',len(x_test))
def construct_model(type):
    if type == 'Native':
      model = Sequential()
      model.add(Conv2D(32, (2, 2), input_shape=(image_size[0],image_size[1], 3), activation='relu'))
      model.add(MaxPool2D(pool_size=(2, 2)))
      model.add(Conv2D(64, (2, 2), activation='relu'))
      model.add(MaxPool2D(pool_size=(2, 2)))
      model.add(Conv2D(128, (2, 2), activation='relu'))
      model.add(MaxPool2D(pool_size=(2, 2)))
      model.add(Flatten())
      model.add(Dropout(0.1))
      model.add(Dense(128, activation='relu'))
      model.add(Dense(64, activation='relu'))
      model.add(Dense(32, activation='relu'))
      model.add(Dense(getClassesNum(), activation='softmax'))
    
    elif type == 'Xception':
      from keras.applications.xception import Xception
      from keras.layers import GlobalMaxPooling2D

      base_model = Xception(include_top=False)
      print('----------------------------- ',len(base_model.layers),'---------------------------')
      x = base_model.output
      x = GlobalMaxPooling2D()(x)
      x = Dense(1024, activation='relu')(x)
      predictions = Dense(getClassesNum(), activation='softmax')(x)
      model = Model(inputs=base_model.input, outputs=predictions)
      for layer in base_model.layers[0:130]:
        layer.trainable = False
      for layer in base_model.layers[130:]:
        layer.trainable = True

    model.compile(optimizer = 'adam', loss = 'categorical_crossentropy', metrics = ['accuracy'])
    model.summary()
  

    return model

print('Xception')
model = construct_model('Xception')


'''######################## Initializing Training Callbacks ########################'''
def init_callbacks():
    from keras.callbacks import CSVLogger, ModelCheckpoint, EarlyStopping, ReduceLROnPlateau
    trained_models_path = base_path + 'model_weights'
    model_names = trained_models_path + '.{epoch:04d}--{val_loss:.4f}--{val_accuracy:.4f}.h5'
    model_checkpoint = ModelCheckpoint(model_names, monitor = 'val_accuracy', verbose=1,save_best_only=True)

    callbacks = [model_checkpoint]
    return callbacks
  
  
  def plot_history(history):
    loss_list = [s for s in history.history.keys() if 'loss' in s and 'val' not in s]
    val_loss_list = [s for s in history.history.keys() if 'loss' in s and 'val' in s]
    acc_list = [s for s in history.history.keys() if 'acc' in s and 'val' not in s]
    val_acc_list = [s for s in history.history.keys() if 'acc' in s and 'val' in s]
 
    if len(loss_list) == 0:
        print('Loss is missing in history')
        return
 
            ## As loss always exists
    epochs = range(1, len(history.history[loss_list[0]]) + 1)
 
        ## Loss
    plt.figure(1)
    for l in loss_list:
        plt.plot(epochs, history.history[l], 'b',
                     label='Training loss (' + str(str(format(history.history[l][-1], '.5f')) + ')'))
    for l in val_loss_list:
        plt.plot(epochs, history.history[l], 'g',
                     label='Validation loss (' + str(str(format(history.history[l][-1], '.5f')) + ')'))
 
    plt.title('Loss')
    plt.xlabel('Epochs')
    plt.ylabel('Loss')
    plt.legend()
 
        ## Accuracy
    plt.figure(2)
    for l in acc_list:
        plt.plot(epochs, history.history[l], 'b',
                    label='Training accuracy (' + str(format(history.history[l][-1], '.5f')) + ')')
    for l in val_acc_list:
        plt.plot(epochs, history.history[l], 'g',
                     label='Validation accuracy (' + str(format(history.history[l][-1], '.5f')) + ')')
 
    plt.title('Accuracy')
    plt.xlabel('Epochs')
    plt.ylabel('Accuracy')
    plt.legend()
    plt.show()
    
    '''######################## Train the model ########################'''
!rm -r '/content/model_weights/'
!mkdir '/content/model_weights/'

def train_model():
  from keras.preprocessing.image import ImageDataGenerator
  train_datagen = ImageDataGenerator(featurewise_center=False, samplewise_center=False, featurewise_std_normalization=False,
        samplewise_std_normalization=False, zca_whitening=False, rotation_range=10, zoom_range = 0.1, width_shift_range=0.1,
        height_shift_range=0.1, horizontal_flip=False, vertical_flip=False)
  #history =model.fit( x_train, y_train, batch_size = batch_size, epochs = epochs, verbose=1, callbacks= init_callbacks(), validation_data=(x_test,y_test))
  history = model.fit(train_datagen.flow(x_train, y_train, batch_size),  steps_per_epoch=len(x_train) / batch_size,  epochs=epochs, verbose=1, callbacks= init_callbacks(), validation_data=(x_test, y_test))
  test_loss,test_acc= model.evaluate(x_test, y_test)
  print("\nTest accuracy: ",test_acc)
  #  print("\nTest loss: ",test_loss)

  plot_history(history)
  plot_model
  
train_model();


import glob
weights = []
weights = glob.glob("/content/model_weights/*")
weights.sort(reverse=True)
print(weights)

def load_existing_model_weights():
    #model = construct_model('Native')
    import os
    from pathlib import Path
    user_home = str(Path.home())
    model.load_weights(weights[0])
    return model

model = load_existing_model_weights()


from tensorflow.keras.preprocessing import image
def load_image(file) :
    try:
        img = cv2.imread(file)
        img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        img = cv2.resize(img,image_size)
        train_images = np.zeros((x_train,image_size,image_size,3), dtype='uint8')
        train_labels = np.zeros((x_train,1), dtype='uint8')

        test_images = np.zeros((x_test, image_size, image_size, image_size,3),dtype='uint8')
        test_labels = np.zeros((x_test,  1),dtype='uint8')

        for i in range(x_train):
          if i % 2 == 0:
            original_image = cv2.imread(perio_path+"periodontal-"+str(i+1)+".png")
            train_images[i] = 1
          else:
            original_image = cv2.imread(non_perio_path+"Non-periodontal-"+str(i+1)+".png")
            train_labels[i] = 0    
          resized_image = cv2.resize(original_image,(image_size,image_size))
          train_images[i] = resized_image
     
        for i in range(x_test):
          if i % 2 == 0:
            original_image = cv2.imread(perio_path + "periodontal-"+str(i + 1 + x_train)+".png")
            test_labels[i] = 1
          else:
            original_image = cv2.imread(non_perio_path + "Normal-" + str(i + 1 + x_train) + ".png")
            test_labels[i] = 0
          resized_image = cv2.resize(original_image, (image_size, image_size))
          test_images[i] = resized_image

        return img
    except:
        return None

test_image =load_image('/content/6.png')


def classify_teeth():
    img = np.asarray(test_image)
    #img= img/255.0
    img=img.reshape(-1,128,128,3)
    #test_loss,test_acc=model.evaluate(test_image,test_labels)
    #print('\nTest accuracy:',test_acc)
    #print('\nTest loss:',test_loss)
    prediction = model.predict(img)
    print(prediction)
    max_pred = tf.argmax(prediction,axis=1)
    print('sum test: ',sum(sum(prediction)))
    print(max_pred)
    return np.argmax(model.predict(img), axis=-1)
    

cls = classify_teeth()

print(cls)
